
package lab.ticket;

import java.awt.Component;
import java.awt.EventQueue;
import java.awt.FlowLayout;
import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.GridLayout;
import java.awt.Insets;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.math.BigDecimal;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import javax.swing.UIManager;
import javax.swing.border.TitledBorder;
import javax.swing.text.DefaultCaret;
//import javax.swing.text.AbstractDocument.BranchElement;


import lab.ticket.model.InstructionData;
import lab.ticket.model.InfoData;
import lab.ticket.service.NIOClient;
import lab.ticket.service.socket.Connector;
import lab.ticket.view.InstructionPanel;

import org.apache.commons.lang3.StringUtils;
import org.dom4j.Document;
import org.dom4j.DocumentException;
import org.dom4j.Element;
import org.dom4j.io.SAXReader;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.alibaba.fastjson.JSON;

import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.locks.Lock; 
import java.util.concurrent.locks.ReentrantLock;
/**
 * 由Eclipse WindowBuilder插件设计UI原型。
 */
public class MainFrame extends JFrame {

	private static final long serialVersionUID = -6541864654653129335L;
	private static final Logger logger = LoggerFactory.getLogger(InstructionPanel.class);
 
	private Thread t1;//nio 线程
	private static ExecutorService pool = Executors.newCachedThreadPool();// 发送线程池
	private static ExecutorService pool1 = Executors.newCachedThreadPool();// 发送线程池
	private static ExecutorService pool2 = Executors.newCachedThreadPool();// 发送线程池
	private static ExecutorService pool3 = Executors.newCachedThreadPool();// 发送线程池
	private static ExecutorService pool4 = Executors.newCachedThreadPool();// 发送线程池
	private static ExecutorService pool5 = Executors.newCachedThreadPool();// 发送线程池
	private static final DateFormat df = new SimpleDateFormat("HH:mm:ss.SSS");

	private static MainFrame frame;
	private JPanel m_contentPane;
	private JPanel panel_4;
	private JLabel lblNewLabel_1;
	private JLabel lblNewLabel_2;
	private JLabel lblNewLabel_3;
	private JLabel lblNewLabel_4;
	private JTextField ip;
	private JTextField port;
	private JTextField port2;
	private JTextField playerCount;
	private JPanel InstructionPanelContainer;
	private JPanel panelLogger;
	private InstructionPanel defaultInstructionPanel;
	private static JScrollPane scrollPaneLogger;
	private static JTextArea consoleArea;
	private JPanel panelOperation;
	private JButton btnStart;
	private JButton btnStop;
	public JButton btnPlayerLogin;
	public static Lock lock = new ReentrantLock();
	
    
	private ArrayList<MyThread> myThread = new ArrayList<MyThread>();

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					frame = new MainFrame();
					frame.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the frame.
	 */
	public MainFrame() {
		final String filePath = System.getProperty("user.dir") + File.separator + "12306.dat";
		final String moduleInfo = System.getProperty("user.dir") + File.separator + "base_module_sub.xml";
		
		addWindowListener(new WindowAdapter() {
			@Override
			public void windowClosing(WindowEvent event) {
				try {
					ObjectOutputStream out = new ObjectOutputStream(new FileOutputStream(filePath));
					out.writeObject(frame.bindUItoModel());
					out.close();
					logger.debug("Saved UI data to file: {}", filePath);
				} catch (Exception e) {
					logger.error("Save UI data to file error", e);
				}
			}

			@Override
			public void windowOpened(WindowEvent event) {
				try {
					// 基于上次保存的dat数据文件恢复UI组件初始值
					File inFile = new File(filePath);
					if (!inFile.exists()) {
						return;
					}
					ObjectInputStream in = new ObjectInputStream(new FileInputStream(inFile));
					InfoData infoData = (InfoData) in.readObject();
					in.close();
					logger.debug("Loaded UI data from file: {}", filePath);
					ip.setText(infoData.getIp());
					port.setText(infoData.getPort());
					port2.setText(infoData.getPortEnd());
					List<InstructionData> instructions = infoData.getPassengerDatas();
					if (instructions != null && instructions.size() > 0) {
						for (int i = instructions.size()-1; i >=0 ; i--) {
							InstructionData instruction = instructions.get(i);
							if (i == instructions.size()-1) {
								defaultInstructionPanel.bindModeltoUI(instruction, true);
							} else {
								InstructionPanel instructionPanel = addInstructionPanel();
								instructionPanel.bindModeltoUI(instruction);
							}
						}
					}
///////////////////////加载模块信息///////////////////
					File inputXml = new File(moduleInfo);
			        if (!inputXml.exists()) {
			        	JOptionPane.showMessageDialog(frame, "模块配置文件不存在！");
			            return;
			        }
		            SAXReader saxReader = new SAXReader();
		            saxReader.setEncoding("UTF-8");
		            Document document;
					try {
						document = saxReader.read(inputXml);
					} catch (DocumentException e1) {
						JOptionPane.showMessageDialog(frame, "模块配置文件解析错误！");
						return;
					}
					
		            Element employees = document.getRootElement();
		            for (Iterator moduleIterator = employees.elementIterator(); moduleIterator.hasNext();) {
		                Element element = (Element) moduleIterator.next();
		                int moduleId = 0;
		                String moduleName = "";
		                for (Iterator elementIterator = element.elementIterator(); elementIterator.hasNext();) {
		                	Element node = (Element) elementIterator.next();
		                	String name = node.getName();
		                	if(name.equals("test_modlue"))
		                		moduleName = node.getText();
		                	if(name.equals("module_id"))
		                		moduleId = Integer.valueOf(node.getText());
		                }
		                
		                if(moduleName.equals(""))
		                	continue;
//		                System.out.println(moduleName+"=="+moduleId);
		                Connector.getConnector().addModule(moduleName, moduleId);
		            }
		            document = null;
		            inputXml = null;
					
				} catch (Exception e) {
					logger.error("Load UI data from file error", e);
				}
			}
		});

		setTitle("测试工具.");
//		setExtendedState(JFrame.MAXIMIZED_BOTH);
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		setBounds(100, 100, 1060, 450);
		m_contentPane = new JPanel();
		setContentPane(m_contentPane);
		GridBagLayout gbl_m_contentPane = new GridBagLayout();
		gbl_m_contentPane.columnWidths = new int[] { 1024, 0 };
		gbl_m_contentPane.rowHeights = new int[] { 50, 100, 0, 39, 148, 0 };
		gbl_m_contentPane.columnWeights = new double[] { 1.0, Double.MIN_VALUE };
		gbl_m_contentPane.rowWeights = new double[] { 0.0, 0.0, 0.0, 0.0, 1.0, Double.MIN_VALUE };
		m_contentPane.setLayout(gbl_m_contentPane);

		panel_4 = new JPanel();
		GridBagConstraints gbc_panel_4 = new GridBagConstraints();
		gbc_panel_4.fill = GridBagConstraints.BOTH;
		gbc_panel_4.insets = new Insets(0, 0, 5, 0);
		gbc_panel_4.gridx = 0;
		gbc_panel_4.gridy = 0;
		m_contentPane.add(panel_4, gbc_panel_4);
		panel_4.setBorder(new TitledBorder(UIManager.getBorder("TitledBorder.border"),
				"第一步：设置ip/端口", TitledBorder.LEADING, TitledBorder.TOP,
				null, null));
		GridBagLayout gbl_panel_4 = new GridBagLayout();
		gbl_panel_4.columnWidths = new int[] { 60, 30, 30, 40, 40, 80, 50, 10, 0, 0 };
		gbl_panel_4.rowHeights = new int[] { 37, 0 };
		gbl_panel_4.columnWeights = new double[] { 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, Double.MIN_VALUE };
		gbl_panel_4.rowWeights = new double[] { 0.0, Double.MIN_VALUE };
		panel_4.setLayout(gbl_panel_4);

		lblNewLabel_1 = new JLabel("IP：");
		GridBagConstraints gbc_lblNewLabel_1 = new GridBagConstraints();
		gbc_lblNewLabel_1.insets = new Insets(0, 0, 0, 5);
		gbc_lblNewLabel_1.anchor = GridBagConstraints.EAST;
		gbc_lblNewLabel_1.gridx = 0;
		gbc_lblNewLabel_1.gridy = 0;
		panel_4.add(lblNewLabel_1, gbc_lblNewLabel_1);

		ip = new JTextField();
		ip.setToolTipText("必须填写IP");
		GridBagConstraints gbc_textTrainFrom = new GridBagConstraints();
		gbc_textTrainFrom.insets = new Insets(0, 0, 0, 5);
		gbc_textTrainFrom.fill = GridBagConstraints.HORIZONTAL;
		gbc_textTrainFrom.gridx = 1;
		gbc_textTrainFrom.gridy = 0;
		panel_4.add(ip, gbc_textTrainFrom);
		ip.setColumns(8);

		lblNewLabel_2 = new JLabel("端口：");
		GridBagConstraints gbc_lblNewLabel_2 = new GridBagConstraints();
		gbc_lblNewLabel_2.insets = new Insets(0, 0, 0, 5);
		gbc_lblNewLabel_2.anchor = GridBagConstraints.EAST;
		gbc_lblNewLabel_2.gridx = 2;
		gbc_lblNewLabel_2.gridy = 0;
		panel_4.add(lblNewLabel_2, gbc_lblNewLabel_2);

		port = new JTextField("8010");
		port.setToolTipText("必须填写端口");
		GridBagConstraints gbc_textTrainTo = new GridBagConstraints();
		gbc_textTrainTo.fill = GridBagConstraints.HORIZONTAL;
		gbc_textTrainTo.insets = new Insets(0, 0, 0, 5);
		gbc_textTrainTo.gridx = 3;
		gbc_textTrainTo.gridy = 0;
		panel_4.add(port, gbc_textTrainTo);
		port.setColumns(6);
		
		port2 = new JTextField("8020");
		GridBagConstraints gbc_formattedTextField = new GridBagConstraints();
		gbc_formattedTextField.insets = new Insets(0, 0, 0, 5);
		gbc_formattedTextField.fill = GridBagConstraints.HORIZONTAL;
		gbc_formattedTextField.gridx = 4;
		gbc_formattedTextField.gridy = 0;
		port2.setColumns(6);
		panel_4.add(port2, gbc_formattedTextField);
		
		lblNewLabel_3 = new JLabel("玩家数量：");
		GridBagConstraints gbc_lblNewLabel_3 = new GridBagConstraints();
		gbc_lblNewLabel_3.insets = new Insets(0, 0, 0, 5);
		gbc_lblNewLabel_3.anchor = GridBagConstraints.EAST;
		gbc_lblNewLabel_3.gridx = 5;
		gbc_lblNewLabel_3.gridy = 0;
		panel_4.add(lblNewLabel_3, gbc_lblNewLabel_3);
		
		playerCount = new JTextField();
		playerCount.setText("1");
		GridBagConstraints gbc_playerCount = new GridBagConstraints();
		gbc_playerCount.fill = GridBagConstraints.HORIZONTAL;
		gbc_playerCount.insets = new Insets(0, 0, 0, 5);
		gbc_playerCount.gridx = 6;
		gbc_playerCount.gridy = 0;
		panel_4.add(playerCount, gbc_playerCount);
		playerCount.setColumns(4);
		
		btnPlayerLogin = new JButton("玩家登录");
		btnPlayerLogin.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				
				String ipStr = ip.getText();
				if(StringUtils.isBlank(ipStr)){
					JOptionPane.showMessageDialog(frame, "ip不能为空，请重新输入");
					return;
				}
				if(StringUtils.isBlank(port.getText())){
					JOptionPane.showMessageDialog(frame, "端口不能为空，请重新输入");
					return;
				}
				
				int portStar = Integer.parseInt(port.getText());
				int portEnd = StringUtils.isBlank(port2.getText())?portStar:Integer.parseInt(port2.getText());
	 
				btnPlayerLogin.setEnabled(false);
				Connector.getConnector().getConnectMap().clear();
				Connector.getConnector().setOver(false);
				Connector.getConnector().getPlayerCount().set(0);
				int pc = Integer.valueOf(playerCount.getText());
				try {
					for (int i = 0; i < pc; i++) {
						if(Connector.getConnector().getClientMap().size()>pc)
							return;
						
						double num = portStar + (Math.random()*(portEnd-portStar));
						BigDecimal   b   =   new   BigDecimal(num);
						int   sendPort   =   b.setScale(0,BigDecimal.ROUND_HALF_UP).intValue();
						
						System.out.println("端口："+sendPort);
						NIOClient clientService = new NIOClient(ipStr,sendPort , pc,frame);
						t1 = new Thread(clientService);
						t1.start();
					}
				} catch (Exception e2) {
					Thread.currentThread().interrupt();
					e2.printStackTrace();
				}finally{
				}
			}
		});
		panel_4.add(btnPlayerLogin);
		
		
		lblNewLabel_4 = new JLabel(" 服务器响应总数：00,上一秒响应数：00");
		GridBagConstraints gbc_label_4 = new GridBagConstraints();
		gbc_label_4.fill = GridBagConstraints.HORIZONTAL;
		gbc_label_4.insets = new Insets(0, 0, 0, 5);
		gbc_label_4.gridx = 8;
		gbc_label_4.gridy = 0;
		panel_4.add(lblNewLabel_4, gbc_label_4);
		
		InstructionPanelContainer = new JPanel();
		InstructionPanelContainer.setBorder(new TitledBorder(UIManager.getBorder("TitledBorder.border"),
				"第二步：设置指令", TitledBorder.LEADING,
				TitledBorder.TOP, null, null));
		
		GridBagConstraints gbc_InstructionPanelContainer = new GridBagConstraints();
		gbc_InstructionPanelContainer.fill = GridBagConstraints.BOTH;
		gbc_InstructionPanelContainer.insets = new Insets(0, 0, 5, 0);
		gbc_InstructionPanelContainer.gridx = 0;
		gbc_InstructionPanelContainer.gridy = 1;
		m_contentPane.add(InstructionPanelContainer, gbc_InstructionPanelContainer);
		InstructionPanelContainer.setLayout(new GridLayout(0, 1, 0, 0));

		panelOperation = new JPanel();
		GridBagConstraints gbc_panelOperation = new GridBagConstraints();
		gbc_panelOperation.fill = GridBagConstraints.BOTH;
		gbc_panelOperation.insets = new Insets(0, 0, 0, 0);
		gbc_panelOperation.gridx = 0;
		gbc_panelOperation.gridy = 2;
		m_contentPane.add(panelOperation, gbc_panelOperation);
		panelOperation.setLayout(new FlowLayout(FlowLayout.CENTER, 5, 5));

		btnStart = new JButton("开始...");
		btnStart.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				MainFrame.appendMessage("-------填写信息检查---开始---------");
				Map<String, NIOClient> connectMap = Connector.getConnector().getClientMap();
				if(connectMap.size()<=0){
					JOptionPane.showMessageDialog(frame, "请先登录！");
					return;
				}
				
				//保存指令以便后面使用
				Connector.getConnector().getInstructionMap().clear();
				for (Component component : InstructionPanelContainer.getComponents()) {
					InstructionData  instructionData = ((InstructionPanel) component).bindUItoModel();
					String module = instructionData.getModule().trim();
					
					if(module.equals("")){
						JOptionPane.showMessageDialog(frame, "模块名不能空！");
						return;
					}
					Map<String, Integer> moduleMap = Connector.getConnector().getModuleMap();
					Integer moduleId = moduleMap.get(module);
					
					//行走模块特殊
					if(module.equals("api.map"))
						moduleId = -4;
					
					if(moduleId == null){
						JOptionPane.showMessageDialog(frame, module+"模块xml中无配置");
						return;
					}
					if(Connector.getConnector().getInstructionMap().containsKey(moduleId)){
						JOptionPane.showMessageDialog(frame, "有重复的模块！");
						return;
					}
						
					//初始化 指令块  用于接收数据的显示
					Connector.getConnector().getInstructionMap().put(moduleId, (InstructionPanel) component);
					//初始化模块响应统计
					AtomicInteger atomicInteger = new AtomicInteger(0);
					Connector.getConnector().setModuleResponseMap(moduleId, atomicInteger);
					//初始化模块发送统计
					AtomicInteger atomicIntegerRequest = new AtomicInteger(0);
					Connector.getConnector().setModuleRequestMap(moduleId,atomicIntegerRequest);
					
				}
				Connector.getConnector().getIds().set(0);//总数设为0
				
				
				int i=0;
				for (String playerName : connectMap.keySet()) {
					for (Component component : InstructionPanelContainer.getComponents()) {
						InstructionData  instructionData = ((InstructionPanel) component).bindUItoModel();
						int count = instructionData.getCount();
						int frequency = instructionData.getFrequency();
						String module = instructionData.getModule().trim();
						String fun = instructionData.getFun().trim();
						String parameters = instructionData.getParameters().trim();
						
						String	instruction = "{\"fun\":\"\",\"module\":\"\",\"parameter\":[],\"type\":\"python\"}";
						try {
							Integer moduleId = 0;
							Map m = (Map)JSON.parse(instruction);
							if(module.equals("api.map")){
								moduleId = -4;
							}else{
								if(!module.equals(""))
									m.put("module", module);
								if(!fun.equals(""))
									m.put("fun", fun);
								if(!parameters.equals("")){
									String[] parametersArray = parameters.split(",");
									List parametersList = Arrays.asList(parametersArray);
									m.put("parameter", parametersList);
								}
								moduleId = Connector.getConnector().getModuleMap().get(module);
							}
							String sendStr = JSON.toJSONString(m);
							MyThread mt = new MyThread(sendStr, count, frequency,playerName,moduleId,((InstructionPanel) component));
							
							if(i<2000){
								pool.execute(mt);	
							}else if (i<4000) {
								pool1.execute(mt);
							}else if (i<6000) {
								pool2.execute(mt);
							}else if (i<8000) {
								pool3.execute(mt);
							}else if (i<10000) {
								pool4.execute(mt);
							}else {
								pool5.execute(mt);
							}
					        myThread.add(mt);
					        i++;
						} catch (Exception e2) {
							MainFrame.appendMessage(e2.toString()+"--指令错误~");
							e2.printStackTrace();
//							Thread.currentThread().interrupt();
						}
					}
				}
			 
				MainFrame.appendMessage("-------填写信息检查---完毕---------");
				btnStart.setEnabled(false);
				btnStop.setEnabled(true);
				
				
				ShowMsg sm = new ShowMsg();
				t1 = new Thread(sm);
				t1.start();
			}
		});
		btnStop = new JButton("停止...");
		btnStop.setEnabled(false);
		btnStop.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				btnStop.setEnabled(false);
				btnStart.setEnabled(true);
				btnPlayerLogin.setEnabled(true);
				Map<String, NIOClient> connectMap = Connector.getConnector().getClientMap();
				for (NIOClient client : connectMap.values()) {
					try {
						client.close();
					} catch (IOException e1) {
						frame.appendMessage(e1.toString());
						e1.printStackTrace();
					}
				}
				connectMap.clear();
				Connector.getConnector().getInstructionMap().clear();
				Connector.getConnector().getModuleLastRequestMap().clear();
				Connector.getConnector().getModuleLastResponseMap().clear();
				Connector.getConnector().getModuleRequestMap().clear();
				Connector.getConnector().getModuleResponseMap().clear();
				
				Connector.getConnector().setOver(false);
				for (MyThread mt : myThread) {
					mt.setReturn(true);
				}
				t1.interrupt();
			}
		});
		
		panelOperation.add(btnStart);
		panelOperation.add(btnStop);
		panelLogger = new JPanel();
		panelLogger.setBorder(new TitledBorder(null, "运行记录", TitledBorder.LEADING,
				TitledBorder.TOP, null, null));
		GridBagConstraints gbc_panelLogger = new GridBagConstraints();
		gbc_panelLogger.fill = GridBagConstraints.BOTH;
		gbc_panelLogger.gridx = 0;
		gbc_panelLogger.gridy = 4;
		m_contentPane.add(panelLogger, gbc_panelLogger);
		panelLogger.setLayout(new GridLayout(0, 1, 0, 0));

		scrollPaneLogger = new JScrollPane();
		panelLogger.add(scrollPaneLogger);

		consoleArea = new JTextArea();
		scrollPaneLogger.setViewportView(consoleArea);
		DefaultCaret caret = (DefaultCaret) consoleArea.getCaret();
		caret.setUpdatePolicy(DefaultCaret.ALWAYS_UPDATE);

		defaultInstructionPanel = new InstructionPanel(this);
		InstructionPanelContainer.add(defaultInstructionPanel);
	}

	public InstructionPanel addInstructionPanel() {
		InstructionPanel instructionPanel = new InstructionPanel(this, true);
		int rowHeight = ((GridBagLayout) m_contentPane.getLayout()).rowHeights[1];
		((GridBagLayout) m_contentPane.getLayout()).rowHeights[1] = instructionPanel.getHeight() + rowHeight;
		InstructionPanelContainer.add(instructionPanel,0);
		m_contentPane.validate();
		return instructionPanel;
	}

	public void removeInstructionPanel(InstructionPanel userPanel) {
		int rowHeight = ((GridBagLayout) m_contentPane.getLayout()).rowHeights[1];
		((GridBagLayout) m_contentPane.getLayout()).rowHeights[1] = rowHeight - userPanel.getHeight();
		InstructionPanelContainer.remove(userPanel);
		m_contentPane.validate();
	}


	/**
	 * 追加显示日志消息
	 * @param message
	 */
	public static synchronized void appendMessage(String message) {
//		lock.lock();
//		logger.debug(message);
		if (consoleArea != null) {
			consoleArea.setText(consoleArea.getText() + df.format(new Date()) + ": " + message + "\n");
		}
//		lock.unlock();
	}

	/**
	 * 绑定UI数据到模型对象
	 * @return
	 */
	private InfoData bindUItoModel() {
		InfoData InfoData = new InfoData();
		InfoData.setIp(ip.getText());
		InfoData.setPort(port.getText());
		InfoData.setPortEnd(port2.getText());
		for (Component component : InstructionPanelContainer.getComponents()) {
			InfoData.getPassengerDatas().add(((InstructionPanel) component).bindUItoModel());
		}
		return InfoData;
	}

	
	public class MyThread extends Thread {
		String sendData = null;
		int count = 0;
		int frequency = 1000;
		InstructionPanel component;
		boolean isReturn=false;
		String playerName;
		int moduleId;
		public MyThread(String sendData,int count,int frequency,String playerName,int moduleId,InstructionPanel component){
			this.sendData = sendData;
			this.count = count;
			this.frequency = frequency<100 ?100 :frequency;
			this.component = component;
			this.playerName = playerName;
			this.moduleId = moduleId;
		}
		@Override
		public void run() {
			NIOClient c = Connector.getConnector().getClientMap().get(playerName);
			if(c==null)
				return;
			while (true) {
				try {
					if(Connector.getConnector().isOver())
						break;
					Thread.sleep(500);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
			for(int i=1;i<=count;i++){
				if(isReturn)
					return;
				try {
					//地图行走指令
					
					if(moduleId == -4){
						int x = (int)(Math.random()*2000);
						int y = (int)(Math.random()*2000);
						c.write1(moduleId,x,y);
					}else{
						c.write(moduleId,sendData);
					}
					Connector.getConnector().getModuleRequestMap().get(moduleId).incrementAndGet();//请求数量
//					component.setRemaining(i);
					Thread.sleep(frequency);
				} catch (Exception e) {
					appendMessage(e.toString());
					e.printStackTrace();
					return;
				}
			}
		}
		public void setReturn(boolean isReturn) {
			this.isReturn = isReturn;
		}
	}
	public void setShowMsg(String msg){
		lblNewLabel_4.setText(msg);
	}
	
	public class ShowMsg extends Thread{
		Connector c = Connector.getConnector();
		Map<Integer, InstructionPanel>  instructionMap =  c.getInstructionMap();
		//请求
		Map<Integer, AtomicInteger> ModuleRequestMap = c.getModuleRequestMap();
		Map<Integer, Long>  moduleLastRequestMap = c.getModuleLastRequestMap();
		//响应
		Map<Integer, AtomicInteger> ModuleResponseMap = c.getModuleResponseMap();
		Map<Integer, Long>  moduleLastResponseMap = c.getModuleLastResponseMap();
		
		@Override
		public void run() {
			while (true) {
				try {
					if(ModuleResponseMap.size()==0 || instructionMap.size()==0)
						return;
					/// 响应总数
					long totalCount = c.getIds().get();//响应总数
				 	String showStr = " 服务器响应总数："+totalCount+",上一秒响应数："+(totalCount-c.getLastTimeNum());
				 	c.setLastTimeNum(totalCount);
	            	setShowMsg(showStr);
	            	
	            	//模块请求响应数
					for (Integer moduleId : instructionMap.keySet()) {
						long requestNum = ModuleRequestMap.get(moduleId).get();//模块请求总数
						Long requestLastNum = moduleLastRequestMap.get(moduleId);//模块上一秒响请求数
						if(requestLastNum == null)requestLastNum=(long)0;
						moduleLastRequestMap.put(moduleId, requestNum);
						
						long responseNum = ModuleResponseMap.get(moduleId).get();//模块响应
						Long responseLastNum = moduleLastResponseMap.get(moduleId);
						if(responseLastNum == null)responseLastNum=(long)0;
						moduleLastResponseMap.put(moduleId, responseNum);
						
						InstructionPanel panel = instructionMap.get(moduleId);
						String showModuleRequestMsg = " 请求数："+requestNum+",上一秒："+(requestNum-requestLastNum);
						panel.setRemaining(showModuleRequestMsg);
						String showModuleMsg = "| 响应数："+responseNum+",上一秒："+(responseNum-responseLastNum); 
						panel.setShowMsg(showModuleMsg);
					}
					Thread.sleep(1000);
				} catch (Exception e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
		}
	}
	

}
