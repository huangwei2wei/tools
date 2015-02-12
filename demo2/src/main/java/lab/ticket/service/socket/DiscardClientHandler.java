/*
 * Copyright 2012 The Netty Project
 *
 * The Netty Project licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
package lab.ticket.service.socket;

import java.util.List;
import java.util.UUID;
import java.util.concurrent.locks.Lock;
import java.util.concurrent.locks.ReentrantLock;

import lab.ticket.MainFrame;

import net.sf.json.JSONObject;

import io.netty.channel.ChannelHandlerContext;
import io.netty.channel.ChannelPromise;
import io.netty.channel.SimpleChannelInboundHandler;

/**
 * Handles a client-side channel.
 */
public class DiscardClientHandler extends SimpleChannelInboundHandler<Object> {
	
	private static final String loginStr = "{\"fd_user_id\":0,\"fun\":\"login\",\"module\":\"api.login\",\"parameter\":[\"jd808\",\"mUG958FxpBwGXXbQ\",0,{\"identifier\":\"21232f297a57a5a743894a0e4a801fc3\",\"server_id\":1,\"channel_id\":1,\"user_id\":9999,\"seqid\":0,\"pfkey\":\"\"}],\"return\":\"\",\"sys\":false,\"type\":\"python\"}";
	private  MainFrame mainFrame = null;
	private int playerCount = 1;
	public Lock lock = new ReentrantLock();
	public DiscardClientHandler(int playerCount,MainFrame frame){
		this.playerCount = playerCount;
		this.mainFrame = frame;
	}
	
    @Override
    public void channelActive(ChannelHandlerContext ctx) throws Exception {
    	//与服务端建立连接后
		JSONObject jsonObject = JSONObject.fromObject(loginStr);
		List parameter = (List) jsonObject.get("parameter");
	    UUID uuid = UUID.randomUUID();
	    String playerName = uuid.toString();
	    parameter.set(0, playerName);
	    jsonObject.put("parameter",parameter);
    	String sendStr = jsonObject.toString();
    	ctx.channel().write(sendStr);
    	lock.lock();
    	Connector.getConnector().addConnect(playerName, ctx);
    	if(Connector.getConnector().getConnectMap().size() >= playerCount){
    		mainFrame.appendMessage(">>>>>>>>>>>>>>所有玩家已登录<<<<<<<<<<<<<<<<<"+Connector.getConnector().getConnectMap().size());
    		Connector.getConnector().setOver(true);
    	}
    	lock.unlock();
    }
 
    
    public void channelInactive(ChannelHandlerContext ctx)throws Exception{
    	System.out.println("---- Channel-Inactive ----");
//    	ctx.channel().close();
    }
    
    @Override
    public void messageReceived(ChannelHandlerContext ctx, Object msg) throws Exception {
    	mainFrame.appendMessage(msg.toString());
//    	MessageDemo message = (MessageDemo) msg;
//    	System.out.println("--- client messageReceived --- message is " + message.getHeader() +"--- "+ message.getMsg());
    }
//    @Override
//    public void channelRead(ChannelHandlerContext ctx, Object msg){
//    	MessageDemo message = (MessageDemo) msg;
//    	System.out.println("--- client messageReceived --- message is== " + message.getHeader() +"--- "+ message.getMsg());
//    }

    @Override
    public void exceptionCaught(ChannelHandlerContext ctx, Throwable cause) throws Exception {
    	mainFrame.appendMessage(cause.toString());
    	System.out.println("*********"+cause);
//        ctx.close();
    }

	@Override
	@Skip
	public void close(ChannelHandlerContext ctx, ChannelPromise promise)throws Exception {
		// TODO Auto-generated method stub
		super.close(ctx, promise);
	}
	

}
