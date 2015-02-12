package lab.ticket.service;

import java.nio.ByteOrder;
import java.util.concurrent.locks.Lock;

import lab.ticket.MainFrame;
import lab.ticket.service.socket.Date2DateDecoder;
import lab.ticket.service.socket.Date2DateEncoder;
import lab.ticket.service.socket.Decoder;
import lab.ticket.service.socket.DiscardClientHandler;
import lab.ticket.service.socket.Encoder;
import io.netty.bootstrap.Bootstrap;
import io.netty.buffer.ByteBuf;
import io.netty.channel.ChannelFuture;
import io.netty.channel.ChannelInitializer;
import io.netty.channel.ChannelOption;
import io.netty.channel.EventLoopGroup;
import io.netty.channel.nio.NioEventLoopGroup;
import io.netty.channel.socket.SocketChannel;
import io.netty.channel.socket.nio.NioSocketChannel;

public class SocketClientService implements Runnable{
    private final String host;
    private final int port;
    private EventLoopGroup group;
    private MainFrame frame;
    private int playerCount;

    

    public SocketClientService(String host, int port, int playerCount,MainFrame frame) {
        this.host = host;
        this.port = port;
        this.frame = frame;
        this.playerCount = playerCount;
    }
    
    @SuppressWarnings("static-access")
	@Override
    public void run() {
//    	System.out.println("当前系统order="+ByteOrder.nativeOrder());
    	
    	// 配置客户端NIO线程组
        this.group = new NioEventLoopGroup();
        try {
            Bootstrap b = new Bootstrap();
            b.group(group).channel(NioSocketChannel.class).handler(new ChannelInitializer<SocketChannel>() {
                 @Override
                 public void initChannel(SocketChannel ch) throws Exception {
                	 ch.pipeline().addLast(new Encoder());
                	 ch.pipeline().addLast(new Decoder());
                	 ch.pipeline().addLast(new Date2DateEncoder());
                	 ch.pipeline().addLast(new Date2DateDecoder());
                     ch.pipeline().addLast(new DiscardClientHandler(playerCount,frame));
                 }
             });
//            b.option(ChannelOption.SO_KEEPALIVE, true);
            // 发起异步连接操作
            ChannelFuture f = b.connect(host, port).sync();
//            frame.appendMessage(">>>>链接成功"+this.host+":"+this.port+"<<<<");
            // 等待客户端链路关闭
            f.channel().closeFuture().sync();
    	} catch (Exception e) {
    		frame.appendMessage(">>>>"+e.toString()+"<<<<");
			e.printStackTrace();
		}finally {
        	 // 释放NIO线程组
        	frame.appendMessage(">>>>释放NIO线程组*<<<<");
            group.shutdownGracefully();
        }
    }
    
    
//    public void close(){
//    	System.out.println("释放NIO线程组");
//        this.group.shutdownGracefully();
//    }
    
    
    public static void main(String[] args) throws Exception {
//        new SocketClientService("127.0.0.1", 8089, 256).run();
    }
}
