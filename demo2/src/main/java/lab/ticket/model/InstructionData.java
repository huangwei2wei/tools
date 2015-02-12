package lab.ticket.model;

import java.io.Serializable;

public class InstructionData implements Serializable{
	/**
	 * 
	 */
	private static final long serialVersionUID = 6852174395739345434L;
//	private String instruction;
	private int count;
	private int frequency;
	private String module;
	private String fun;
	private String parameters;
	
	
	
	
//	
//	public String getInstruction() {
//		return instruction;
//	}
//	public void setInstruction(String instruction) {
//		this.instruction = instruction;
//	}
	public int getCount() {
		return count;
	}
	public void setCount(int count) {
		this.count = count;
	}
	public int getFrequency() {
		return frequency;
	}
	public void setFrequency(int frequency) {
		this.frequency = frequency;
	}
	public String getModule() {
		return module;
	}
	public void setModule(String module) {
		this.module = module;
	}
	public String getFun() {
		return fun;
	}
	public void setFun(String fun) {
		this.fun = fun;
	}
	public String getParameters() {
		return parameters;
	}
	public void setParameters(String parameters) {
		this.parameters = parameters;
	}
	 
}
