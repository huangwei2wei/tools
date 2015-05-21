package lab.ticket.service;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.InetSocketAddress;
import java.net.SocketException;
import java.nio.ByteBuffer;
import java.nio.ByteOrder;
import java.nio.channels.SelectionKey;
import java.nio.channels.Selector;
import java.nio.channels.SocketChannel;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.UUID;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.locks.Lock;
import java.util.concurrent.locks.ReentrantLock;

import org.apache.commons.collections.primitives.ArrayByteList;
import org.apache.commons.collections.primitives.ByteList;

import lab.ticket.MainFrame;
import lab.ticket.service.socket.Connector;
import lab.ticket.util.ByteListUtil2;
import lab.ticket.view.InstructionPanel;
import net.sf.json.JSONObject;
 

public class NIOClient implements Runnable{

	/*标识数字*/
	private static int flag = 0;
	/*缓冲区大小*/
	private static int BLOCK = 4096;
	/*接受数据缓冲区*/
	private  ByteBuffer sendbuffer = ByteBuffer.allocate(BLOCK);
	/*发送数据缓冲区*/
	private  ByteBuffer receivebuffer = ByteBuffer.allocate(BLOCK);
	private  SocketChannel socketChannel = null;
	private InetSocketAddress SERVER_ADDRESS = null;
	private static final String loginStr = "{\"fd_user_id\":0,\"fun\":\"login\",\"module\":\"api.login\",\"parameter\":[\"jd808\",\"mUG958FxpBwGXXbQ\",0,{\"identifier\":\"21232f297a57a5a743894a0e4a801fc3\",\"server_id\":2,\"channel_id\":1,\"user_id\":0,\"seqid\":0,\"pfkey\":\"\"}],\"return\":\"\",\"sys\":false,\"type\":\"python\"}";
	
	public Lock lock = new ReentrantLock();
	private int playerCount = 0;
	private MainFrame frame;
	private byte remains[] = null;

	public NIOClient(String ip,Integer port,Integer playerCount, MainFrame frame){
		 SERVER_ADDRESS = new InetSocketAddress(ip, port);
		 this.playerCount = playerCount;
		 this.frame = frame;
//		 run();
	}

	@Override
	public void run()  {
		try {
			Set<SelectionKey> selectionKeys;
			Iterator<SelectionKey> iterator;
			SelectionKey selectionKey;
			SocketChannel client;
			String sendText;
			int count=0;
			
			/*服务器端地址*/
			// 打开socket通道
			socketChannel = SocketChannel.open();
			// 设置为非阻塞方式
			socketChannel.configureBlocking(false);
			// 打开选择器
			Selector selector = Selector.open();
			
			// 注册连接服务端socket动作
			socketChannel.register(selector, SelectionKey.OP_CONNECT);
			// 连接
			socketChannel.connect(SERVER_ADDRESS);
			// 分配缓冲区大小内存
			
			while (true) {
				//选择一组键，其相应的通道已为 I/O 操作准备就绪。
				//此方法执行处于阻塞模式的选择操作。
				selector.select();
				//返回此选择器的已选择键集。
				selectionKeys = selector.selectedKeys();
				iterator = selectionKeys.iterator();
				while (iterator.hasNext()) {
					selectionKey = iterator.next();
					client = (SocketChannel) selectionKey.channel();
//					System.out.println(selectionKey.isConnectable() +" "+ selectionKey.isValid()+" "+selectionKey.isReadable()+" "
//							+selectionKey.isWritable()+" "+selectionKey.isAcceptable());
					
					if (selectionKey.isConnectable()) {
//						System.out.println("client connect");
						// 判断此通道上是否正在进行连接操作。
						// 完成套接字通道的连接过程。
						if (client.isConnectionPending()) {
							client.finishConnect();
							JSONObject jsonObject = JSONObject.fromObject(loginStr);
							List parameter = (List) jsonObject.get("parameter");
						    UUID uuid = UUID.randomUUID();
						    String playerName = uuid.toString();
						    parameter.set(0, playerName);
						    jsonObject.put("parameter",parameter);
					    	String sendStr = jsonObject.toString();
					    	
					    	sendbuffer.clear();
					    	sendbuffer.put(encode2(0, sendStr).toArray());
							sendbuffer.flip();
					    	client.write(sendbuffer);
					    	
					    	lock.lock();
					    	Connector.getConnector().addClient(playerName, this);
					    	if(Connector.getConnector().getClientMap().size() >= playerCount){
					    		frame.appendMessage("连接玩家数: " + Connector.getConnector().getClientMap().size());
					    		Connector.getConnector().setOver(true);
					    	}
					    	lock.unlock();
						}
					} else if (selectionKey.isReadable()) {
						//将缓冲区清空以备下次读取
						receivebuffer.clear();
						//读取服务器发送来的数据到缓冲区中
						count = client.read(receivebuffer);
//						System.out.println("=================="+count + " "+(count<0));
						if(count==-1){
							throw new SocketException("链接已经关闭");
//							client.close();
//							break;
						}
						ByteBuffer buffer = null;
						if(remains != null){
							byte[] data3 = new byte[remains.length + count];
							System.arraycopy(remains,0,data3,0,remains.length);
							System.arraycopy(receivebuffer.array(),0,data3,remains.length,count);
							buffer = ByteBuffer.wrap(data3);
//							System.out.println(buffer.limit()+"==="+"==="+"=="+count+"=="+buffer.remaining()+"=="+buffer.position()+"=="+buffer.capacity());
						}else{
							buffer = receivebuffer;
//							System.out.println(buffer.limit()+"===>>"+"==="+"=="+count+"=="+ buffer.remaining()+"=="+buffer.position()+"=="+buffer.capacity());
							buffer.flip();
//							System.out.println(buffer.limit()+"===<<"+"==="+"=="+count+"=="+ buffer.remaining()+"=="+buffer.position()+"=="+buffer.capacity());
						}
						buffer.order(ByteOrder.LITTLE_ENDIAN);//设置小头在前　默认大头序
						while (buffer.hasRemaining()) {
							int size = buffer.remaining();
//							System.out.println(size);
							if(size > 12){
								buffer.mark();
								int len = buffer.getInt();
								int moduleId = buffer.getInt();
								int code = buffer.getInt();
								size = buffer.remaining();
								if(len<=size){
					                byte dates[] = new byte[len];
					                buffer.get(dates);
					                String str = new String(dates, "utf-8");
					                //玩家登录成功数量
//					                System.out.println("模块ID："+moduleId+" 数据："+str);
					                if(moduleId == 0){
					                	long num = Connector.getConnector().getPlayerCount().incrementAndGet();
					                	frame.appendMessage("玩家成功登录数量： "+num);
					                	//玩家登录成功发送地图跳转指令
					                	sendbuffer.clear();
					                	sendbuffer.put(encode1(-3, 1).toArray());
										sendbuffer.flip();
								    	client.write(sendbuffer);
					                }
					                //统计开始
					                Connector c = Connector.getConnector();
					                AtomicInteger atomicInteger = c.getModuleResponseMap().get(moduleId);
					                // -4行走
					                if(atomicInteger != null){
					                	c.getIds().incrementAndGet();//响应总数
					                	atomicInteger.incrementAndGet();//模块响应总数
					                }else{
//					                	if(moduleId != 0)
//					                		System.out.println("模块"+moduleId+"xml不对应！无需统计");
					                }
					                //统计结束
					                remains = null;
								}else{
									buffer.reset();
									size = buffer.remaining();
									if(size>0){
						                byte bytes[] = new byte[size];
						                buffer.get(bytes);
						                remains = bytes;
//							                System.out.println("#########"+(remains==null)+size);
									}
								}
							}else{
								if (size>0){
					                byte bytes[] = new byte[size];
					                buffer.get(bytes);
					                remains = bytes;
//						                System.out.println("+++++++"+(remains==null)+size);
								}
							}
						}
					} else if (selectionKey.isWritable()) {
						sendbuffer.clear();
						sendText = "message from client--" + (flag++);
						sendbuffer.put(sendText.getBytes());
						 //将缓冲区各标志复位,因为向里面put了数据标志被改变要想从中读取数据发向服务器,就要复位
						sendbuffer.flip();
						client.write(sendbuffer);
						System.out.println("客户端向服务器端发送数据--："+sendText);
					}
					client.register(selector, SelectionKey.OP_READ);
//					socketChannel.register(selector, SelectionKey.OP_READ);
				}
				selectionKeys.clear();
			}
		} catch (Exception e) {
			try {
				socketChannel.close();
			} catch (IOException e1) {
				e1.printStackTrace();
			}
			frame.btnPlayerLogin.setEnabled(true);
			remains=null;
			e.printStackTrace();
			frame.appendMessage(e.toString());
		}
	}
	
	public void close() throws IOException{
		socketChannel.close();
	}
	
	public void write(int moduleid,String str)throws Exception{
		ByteList bl = encode(moduleid, str);
		sendbuffer.clear();
		sendbuffer.put(bl.toArray());
		sendbuffer.flip();
		socketChannel.write(sendbuffer);
	}
	
	public void write1(int moduleid,int x,int y)throws Exception{
		ByteList bl = encode2(moduleid, x,y);
		sendbuffer.clear();
		sendbuffer.put(bl.toArray());
		sendbuffer.flip();
		socketChannel.write(sendbuffer);
	}
	
	public ByteList encode(int moduleid, String str) throws UnsupportedEncodingException {
		str = str+"#$#0728";
		str = new String(str.getBytes(), "utf-8");
		byte[] datas = str.toString().getBytes();
		ByteList byteList = new ArrayByteList(datas.length+12);
		ByteListUtil2.addInt(byteList, datas.length);
		ByteListUtil2.addInt(byteList, moduleid);
		ByteListUtil2.addInt(byteList, getCode(str));
		ByteListUtil2.addBytes(byteList,datas);
		return byteList;
	}
	//地图跳转
	public ByteList encode1(int moduleid, int mapId) throws UnsupportedEncodingException {
		String str = "#$#0728";
		str = new String(str.getBytes(), "utf-8");
		byte[] datas = str.toString().getBytes();
		
		ByteList byteList = new ArrayByteList(12+4+datas.length);
		ByteListUtil2.addInt(byteList, 11);
		ByteListUtil2.addInt(byteList, moduleid);
		ByteListUtil2.addInt(byteList, mapId+getCode(str));
		
		ByteListUtil2.addInt(byteList,mapId);
		ByteListUtil2.addBytes(byteList,datas);
		return byteList;
	}
	//登录头
	public ByteList encode2(int moduleid, String str) throws UnsupportedEncodingException {
		str = str+"#$#0728";
		str = new String(str.getBytes(), "utf-8");
		byte[] datas = str.toString().getBytes();
		ByteList byteList = new ArrayByteList(datas.length+20);
		ByteListUtil2.addInt(byteList, datas.length);
		ByteListUtil2.addInt(byteList, moduleid);
		ByteListUtil2.addInt(byteList, getCode(str));
		ByteListUtil2.addInt(byteList, 1);
		ByteListUtil2.addInt(byteList, 2);
		ByteListUtil2.addBytes(byteList,datas);
		return byteList;
	}
	
	//地图行走
	public ByteList encode2(int moduleid, int x,int y) throws UnsupportedEncodingException {
		String str = "#$#0728";
		str = new String(str.getBytes(), "utf-8");
		byte[] datas = str.toString().getBytes();
		ByteList byteList = new ArrayByteList(12+4+4+datas.length);
		ByteListUtil2.addInt(byteList, 15);
		ByteListUtil2.addInt(byteList, moduleid);
		ByteListUtil2.addInt(byteList, getCode1(x)+getCode1(y)+getCode(str));		
		ByteListUtil2.addInt(byteList,x);
		ByteListUtil2.addInt(byteList,y);
		ByteListUtil2.addBytes(byteList,datas);
		return byteList;
	}

	public int getCode(String str){
		char[] cs = str.toCharArray();
		int i = 0;
		for (char c : cs) {
			i+= (int)c;
		}
		return i;
	}
	
	
	public int getCode1(int num){
        int i =  (byte) (num & 0xFF);
        i += (byte) (num >> 8 & 0xFF);
        i += (byte) (num >> 16 & 0xFF);
        i += (byte) (num >> 24 & 0xFF);
        return i;
	}
	
//	public static void main(String[] args) throws IOException {
//		new NIOClient("localhost", 8010,1);
//	}
}
