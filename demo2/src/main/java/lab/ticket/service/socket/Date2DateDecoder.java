package lab.ticket.service.socket;

import java.util.List;

import io.netty.channel.ChannelHandlerContext;
import io.netty.handler.codec.MessageToMessageDecoder;

@SuppressWarnings("unchecked")
public class Date2DateDecoder extends MessageToMessageDecoder{
	
	@Override
	protected void decode(ChannelHandlerContext ctx, Object msg, List outList)
			throws Exception {
//		System.out.println("---- 数据解码  == Date2Date ----"+msg);
		outList.add(msg);
	}
}
