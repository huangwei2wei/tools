/*
 * 12306-hunter: Java Swing C/S版本12306订票助手
 * 本程序完全开放源代码，仅作为技术学习交流之用，不得用于任何商业用途
 */
package lab.ticket.view;

import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.Insets;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import javax.swing.border.TitledBorder;
import lab.ticket.MainFrame;
import lab.ticket.model.InstructionData; 

/**
 * 登录账号输入UI对象，由Eclipse WindowBuilder插件设计UI原型
 */
public class InstructionPanel extends JPanel {

	private static final long serialVersionUID = 6430934135358072444L;

	private MainFrame container;
	private InstructionPanel self=this;

	private JLabel label_1;
	private JLabel label_2;
	private JLabel label_3;
	private JLabel label_4;
	private JLabel label_module;
	private JLabel label_fun;
	private JLabel label_parameters;
	
	private JTextField frequency;
	private JTextField count;
	private JTextField textModule;
	private JTextField textFun;
	private JTextField textParameters;
	private JPanel panel;
	private JButton btnLineOP;

	/**
	 * Create the panel.
	 */
	public InstructionPanel() {
		setBorder(new TitledBorder(null, "指令", TitledBorder.LEADING, TitledBorder.TOP, null, null));
		GridBagLayout gridBagLayout = new GridBagLayout();
		gridBagLayout.columnWidths = new int[] { 350, 0 };
		gridBagLayout.rowHeights = new int[] { 20, 0 };
		gridBagLayout.columnWeights = new double[] { 0.0, Double.MIN_VALUE };
		gridBagLayout.rowWeights = new double[] { 0.0, Double.MIN_VALUE };
		setLayout(gridBagLayout);

		panel = new JPanel();
		GridBagConstraints gbc_panel = new GridBagConstraints();
		gbc_panel.fill = GridBagConstraints.BOTH;
		gbc_panel.gridx = 0;
		gbc_panel.gridy = 0;
		add(panel, gbc_panel);
		GridBagLayout gbl_panel = new GridBagLayout();
		gbl_panel.columnWidths = new int[] { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
		gbl_panel.rowHeights = new int[] { 0, 0, 0 };
		gbl_panel.columnWeights = new double[] { 0.0, 1.0, Double.MIN_VALUE, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 };
		gbl_panel.rowWeights = new double[] { 0.0, 1.0, Double.MIN_VALUE };
		panel.setLayout(gbl_panel);
 
		btnLineOP = new JButton("增加");
		btnLineOP.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				String op = btnLineOP.getText();
				if ("删除".equals(op)) {
					container.removeInstructionPanel(self);
				} else {
					container.addInstructionPanel();
				}
			}
		});
		GridBagConstraints gbc_btnLineOP = new GridBagConstraints();
		gbc_btnLineOP.insets = new Insets(0, 0, 0, 0);
		gbc_btnLineOP.gridx = 0;
		gbc_btnLineOP.gridy = 0;
		panel.add(btnLineOP, gbc_btnLineOP);
		
		label_module = new JLabel(" 模块：");
		GridBagConstraints gbc_module = new GridBagConstraints();
		gbc_module.insets = new Insets(0, 0, 0, 0);
		gbc_module.gridx = 1;
		gbc_module.gridy = 0;
		panel.add(label_module, gbc_module);
		
		textModule = new JTextField();
		textModule.setColumns(5);
		GridBagConstraints gbc_textModule = new GridBagConstraints();
		gbc_textModule.insets = new Insets(0, 0, 0, 0);
		gbc_textModule.gridx = 2;
		gbc_textModule.gridy = 0;
		panel.add(textModule, gbc_textModule);
		
		label_fun = new JLabel(" 函数：");
		GridBagConstraints gbc_fun = new GridBagConstraints();
		gbc_fun.insets = new Insets(0, 0, 0, 0);
		gbc_fun.gridx = 3;
		gbc_fun.gridy = 0;
		panel.add(label_fun, gbc_fun);
		
		textFun = new JTextField();
		textFun.setColumns(5);
		GridBagConstraints gbc_textFun = new GridBagConstraints();
		gbc_textFun.insets = new Insets(0, 0, 0, 0);
		gbc_textFun.gridx = 4;
		gbc_textFun.gridy = 0;
		panel.add(textFun, gbc_textFun);
		
		label_parameters = new JLabel(" 参数：");
		GridBagConstraints gbc_parameters = new GridBagConstraints();
		gbc_parameters.insets = new Insets(0, 0, 0, 0);
		gbc_parameters.gridx = 5;
		gbc_parameters.gridy = 0;
		panel.add(label_parameters, gbc_parameters);
		
		textParameters = new JTextField();
		textParameters.setColumns(5);
		GridBagConstraints gbc_textParameters = new GridBagConstraints();
		gbc_textParameters.insets = new Insets(0, 0, 0, 0);
		gbc_textParameters.gridx = 6;
		gbc_textParameters.gridy = 0;
		panel.add(textParameters, gbc_textParameters);
		
		label_1 = new JLabel(" 间隔（毫秒）：");
		GridBagConstraints gbc_label_1 = new GridBagConstraints();
		gbc_label_1.fill = GridBagConstraints.HORIZONTAL;
		gbc_label_1.insets = new Insets(0, 0, 0, 0);
		gbc_label_1.gridx = 7;
		gbc_label_1.gridy = 0;
		panel.add(label_1, gbc_label_1);
		
		frequency = new JTextField();
		frequency.setText("1000");
		GridBagConstraints gbc_textLoginUser = new GridBagConstraints();
		gbc_textLoginUser.fill = GridBagConstraints.HORIZONTAL;
		gbc_textLoginUser.insets = new Insets(0, 0, 0, 0);
		gbc_textLoginUser.gridx = 8;
		gbc_textLoginUser.gridy = 0;
		panel.add(frequency, gbc_textLoginUser);
		frequency.setColumns(5);

		
		label_2 = new JLabel(" 次数：");
		GridBagConstraints gbc_label_2 = new GridBagConstraints();
		gbc_label_2.fill = GridBagConstraints.HORIZONTAL;
		gbc_label_2.insets = new Insets(0, 0, 0, 0);
		gbc_label_2.gridx = 9;
		gbc_label_2.gridy = 0;
		panel.add(label_2, gbc_label_2);
		
		count = new JTextField();
		count.setText("100");
		GridBagConstraints gbc2 = new GridBagConstraints();
		gbc2.fill = GridBagConstraints.HORIZONTAL;
		gbc2.insets = new Insets(0, 0, 0, 0);
		gbc2.gridx = 10;
		gbc2.gridy = 0;
		panel.add(count, gbc2);
		count.setColumns(5);
		
		label_3 = new JLabel(" ");
		GridBagConstraints gbc_label_3 = new GridBagConstraints();
		gbc_label_3.fill = GridBagConstraints.HORIZONTAL;
		gbc_label_3.insets = new Insets(0, 0, 0, 0);
		gbc_label_3.gridx = 11;
		gbc_label_3.gridy = 0;
		panel.add(label_3, gbc_label_3);
		
		label_4 = new JLabel("");
		GridBagConstraints gbc_label_4 = new GridBagConstraints();
		gbc_label_4.fill = GridBagConstraints.HORIZONTAL;
		gbc_label_4.insets = new Insets(0, 0, 0, 0);
		gbc_label_4.gridx = 12;
		gbc_label_4.gridy = 0;
		panel.add(label_4, gbc_label_4);
	}
	
	public void setRemaining(String msg){
		label_3.setText(msg);
	}
	public void setShowMsg(String msg){
		label_4.setText(msg);
	}

	public InstructionPanel(MainFrame container) {
		this();
		this.container = container;
	}

	public InstructionPanel(MainFrame container, boolean append) {
		this(container);
		if (append) {
			btnLineOP.setText("删除");
		}
	}

	public InstructionData bindUItoModel() {
		InstructionData instructionData = new InstructionData();
		
		instructionData.setCount(Integer.valueOf(count.getText()));
		instructionData.setFrequency(Integer.valueOf(frequency.getText()));
		instructionData.setModule(textModule.getText());
		instructionData.setFun(textFun.getText());
		instructionData.setParameters(textParameters.getText());
		return instructionData;
	}

	public void bindModeltoUI(InstructionData instruction, boolean passwdFocus) {
		this.count.setText(instruction.getCount()+"");
		this.frequency.setText(instruction.getFrequency()+"");
		this.textModule.setText(instruction.getModule());
		this.textFun.setText(instruction.getFun());
		this.textParameters.setText(instruction.getParameters());
	}

	public void bindModeltoUI(InstructionData instruction) {
		this.bindModeltoUI(instruction, false);
	}
}
