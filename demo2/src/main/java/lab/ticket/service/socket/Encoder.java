package lab.ticket.service.socket;

import java.nio.ByteOrder;
import io.netty.buffer.ByteBuf;
import io.netty.channel.ChannelHandlerContext;
import io.netty.handler.codec.MessageToByteEncoder;


public class Encoder extends MessageToByteEncoder<Object>{
	
	@Override
	protected void encode(ChannelHandlerContext ctx, Object msg, ByteBuf out) throws Exception {
		try {
		    out = out.order(ByteOrder.LITTLE_ENDIAN); 
		    String str = msg+"#$#0728";
			byte[] datas = str.toString().getBytes("utf-8");
			out.writeInt(datas.length);
//			System.out.println((datas.length+7) +" ==== "+ this.bytesToInt(out.readBytes(new byte[4]), 0));
			out.writeInt(1);
			out.writeInt(getCode(str));
			out.writeBytes(datas);
			ctx.flush();
			
		} catch (Exception e) {
			System.out.println("----Encode Error----");
		}
		
		
	}
	public int getCode(String str){
		char[] cs = str.toCharArray();
		int i = 0;
		for (char c : cs) {
			i+= (int)c;
		}
		return i;
	}
	
}
