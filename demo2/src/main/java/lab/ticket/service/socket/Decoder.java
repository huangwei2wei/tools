package lab.ticket.service.socket;

import io.netty.buffer.ByteBuf;
import io.netty.channel.ChannelHandlerContext;
import io.netty.handler.codec.ByteToMessageDecoder;

import java.nio.ByteOrder;
import java.util.List;

public class Decoder extends ByteToMessageDecoder{
    @Override
    public void decode(ChannelHandlerContext ctx, ByteBuf buf, List<Object> out) throws Exception {
    	try {
    		System.out.println("---- 解码 ----");
    		buf = buf.order(ByteOrder.LITTLE_ENDIAN);
    		int readableBytes = buf.readableBytes();
    		if (readableBytes < 4)
    			return;
    		
    		int lenght = buf.readInt();
    		if(readableBytes < lenght+12){
    			buf.resetReaderIndex();
    			return;
    		}
    		
    		int modeId = buf.readInt();
    		int code = buf.readInt();
    		byte[] tmp = new byte[lenght];
    		buf.readBytes(tmp);
    		String str = new String(tmp, "utf-8");
    		
    		System.out.println(readableBytes+"==="+buf.readableBytes()+"--decode length--"+(lenght)+"--modeId--"+modeId+"--code--"+code+"--data--"+str );
    		buf.discardReadBytes();
    		
            out.add(str);  
            
            
            
		} catch (Exception e) {
			System.out.println("错误："+e.toString());
		}
    }
    
    
}
