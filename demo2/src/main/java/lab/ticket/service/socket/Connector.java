package lab.ticket.service.socket;

import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.atomic.AtomicInteger;

import lab.ticket.service.NIOClient;
import lab.ticket.view.InstructionPanel;
import io.netty.channel.ChannelHandlerContext;

public  class Connector {
	private static Connector connector = new Connector();
	private Map<String, ChannelHandlerContext> connectMap = new ConcurrentHashMap<String, ChannelHandlerContext>();
	private Map<String, NIOClient> clientMap = new ConcurrentHashMap<String, NIOClient>();//key:playerName  value: NIOClient
	private Map<String,Integer> moduleMap = new ConcurrentHashMap<String,Integer>();//key:moduleName  value:moduleID
	private Map<Integer,InstructionPanel> instructionMap = new ConcurrentHashMap<Integer,InstructionPanel>();//key:moduleID  value: 指令
	private boolean isOver = false;//用于判断所有用户是否连接成功
	
	private AtomicInteger     ids               = new AtomicInteger(0);// 统计响应总数
	private long lastTimeNum = 0;//上次统计总数
	private Map<Integer,AtomicInteger> moduleResponseMap = new ConcurrentHashMap<Integer, AtomicInteger>();//统计模块的响应总数统计
	private Map<Integer,Long> moduleLastResponseMap = new ConcurrentHashMap<Integer, Long>();//统计模块上一次的响应次数
	
	private Map<Integer,AtomicInteger> moduleRequestMap = new ConcurrentHashMap<Integer, AtomicInteger>();//统计模块的请求总数
	private Map<Integer,Long> moduleLastRequestMap = new ConcurrentHashMap<Integer, Long>();//统计模块上一次的请求次数
	private AtomicInteger playerCount = new AtomicInteger(0);
	
	private Connector (){}
	
	public static Connector getConnector() {
	  return connector;  
	}
    
    public void send(String str,Object arg0){
    	ChannelHandlerContext ctx = connectMap.get(str);
    	if (ctx == null)
    		return;
    	ctx.channel().writeAndFlush(arg0);
    }
    
    public void addConnect(String str,ChannelHandlerContext context){
    	connectMap.put(str, context);
    }

	public Map<String, ChannelHandlerContext> getConnectMap() {
		return connectMap;
	}

	public boolean isOver() {
		return isOver;
	}

	public void setOver(boolean isOver) {
		this.isOver = isOver;
	}
    
    public void addClient(String str,NIOClient client){
    	clientMap.put(str, client);
    }
    
	public Map<String, NIOClient> getClientMap() {
		return clientMap;
	}

	public long getLastTimeNum() {
		return lastTimeNum;
	}

	public void setLastTimeNum(long lastTimeNum) {
		this.lastTimeNum = lastTimeNum;
	}

	public AtomicInteger getIds() {
		return ids;
	}

	public Map<String, Integer> getModuleMap() {
		return moduleMap;
	}
	public void addModule(String moduleName,int moduleId){
		moduleMap.put(moduleName, moduleId);
	}

	public Map<Integer, InstructionPanel> getInstructionMap() {
		return instructionMap;
	}

	public Map<Integer, AtomicInteger> getModuleResponseMap() {
		return moduleResponseMap;
	}

	public void setModuleResponseMap( Integer moduleId, AtomicInteger atomicInteger) {
		this.moduleResponseMap.put(moduleId, atomicInteger);
	}

	public Map<Integer, Long> getModuleLastResponseMap() {
		return moduleLastResponseMap;
	}

	public AtomicInteger getPlayerCount() {
		return playerCount;
	}

	public Map<Integer, AtomicInteger> getModuleRequestMap() {
		return moduleRequestMap;
	}

	public void setModuleRequestMap( Integer moduleId, AtomicInteger atomicInteger) {
		this.moduleRequestMap.put(moduleId, atomicInteger);
	}

	public Map<Integer, Long> getModuleLastRequestMap() {
		return moduleLastRequestMap;
	}	
}
