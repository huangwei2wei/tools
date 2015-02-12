package lab.ticket.service.socket;

import java.util.List;

import io.netty.channel.ChannelHandlerContext;
import io.netty.handler.codec.MessageToMessageEncoder;

@SuppressWarnings("unchecked")
public class Date2DateEncoder extends MessageToMessageEncoder{

	@Override
	protected void encode(ChannelHandlerContext ctx, Object msg, List out)
			throws Exception {
		// TODO Auto-generated method stub
//		System.out.println("------ 数据编码==Date2Date ------");
		out.add(msg);
	}

 

}
