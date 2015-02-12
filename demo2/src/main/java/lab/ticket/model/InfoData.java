/*
 * 12306-hunter: Java Swing C/S版本12306订票助手
 * 本程序完全开放源代码，仅作为技术学习交流之用，不得用于任何商业用途
 */
package lab.ticket.model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;



/**
 * 数据对象
 */
public class InfoData implements Serializable {

	private static final long serialVersionUID = 8111470035162492839L;
	private String ip;
	private String port;
	private String portEnd;
	private List<InstructionData> passengerDatas = new ArrayList<InstructionData>();
	public String getIp() {
		return ip;
	}
	public void setIp(String ip) {
		this.ip = ip;
	}
	public String getPort() {
		return port;
	}
	public void setPort(String port) {
		this.port = port;
	}
	
	public String getPortEnd() {
		return portEnd;
	}
	public void setPortEnd(String portEnd) {
		this.portEnd = portEnd;
	}
	public List<InstructionData> getPassengerDatas() {
		return passengerDatas;
	}
	public void setPassengerDatas(List<InstructionData> passengerDatas) {
		this.passengerDatas = passengerDatas;
	}
	
	
	
	
}

