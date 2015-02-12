package lab.ticket;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.UUID;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;

public class Demo {

	public Demo() {
		// TODO Auto-generated constructor stub
	}

	/**
	 * @param args
	 */
	public static void main(String[] args) {

//	List list = new ArrayList();
//	list.add( "first" );
//	list.add( "second" );
//	JSONArray jsonArray2 = JSONArray.fromObject( list ); 
		
		
		
		String loginStr = "{\"fd_user_id\":0,\"fun\":\"login\",\"module\":\"api.login\",\"parameter\":[\"jd808\",\"mUG958FxpBwGXXbQ\",0,{\"identifier\":\"21232f297a57a5a743894a0e4a801fc3\",\"server_id\":1,\"channel_id\":1,\"user_id\":9999,\"seqid\":0,\"pfkey\":\"\"}],\"return\":\"\",\"sys\":false,\"type\":\"python\"}";
		JSONObject jsonObject = JSONObject.fromObject(loginStr);
		List parameter = (List) jsonObject.get("parameter");
 
 
	    UUID uuid = UUID.randomUUID();
	    parameter.set(0, uuid.toString());
	    System.out.println(uuid);
		
		jsonObject.put("parameter",   parameter);
		System.out.println(jsonObject.toString());
		
//		List parameter = (ArrayList) jsonObject.get("parameter");
		
		
//		System.out.println(parameter.get(1));
		
		
//		String jsonStr = "{\"fd_user_id\":0,\"fun\":\"login\",\"module\":\"api.login\",\"parameter\":[\"jd808\",\"mUG958FxpBwGXXbQ\",0,{\"identifier\":\"21232f297a57a5a743894a0e4a801fc3\",\"server_id\":1,\"channel_id\":1,\"user_id\":9999,\"seqid\":0,\"pfkey\":\"\"}],\"return\":\"\",\"sys\":false,\"type\":\"python\"}";
//		PandantTools.parseJSON2Map(jsonStr);
		
		
		
//		JSONObject json = JSONObject.fromObject(jsonStr);
//		JSONArray array = JSONArray.fromObject(jsonStr);
		
		
//		System.out.println("aaaa");
		
		
		//		 Map<String, Object> map = new HashMap<String, Object>();
//	        //最外层解析
//	        
//	        for(Object k : json.keySet()){
//	            Object v = json.get(k); 
//	            //如果内层还是数组的话，继续解析
//	            if(v instanceof JSONArray){
//	                List<Map<String, Object>> list = new ArrayList<Map<String,Object>>();
//	                Iterator<JSONObject> it = ((JSONArray)v).iterator();
//	                while(it.hasNext()){
//	                    JSONObject json2 = it.next();
////	                    list.add(parseJSON2Map(json2.toString()));
//	                }
//	                map.put(k.toString(), list);
//	            } else {
//	                map.put(k.toString(), v);
//	            }
//	        }
		
	 

		
		
	}
	
	
	
//    public static List<Person> getPersons(String jsonStr)
//    {
//        List<Person> list = new ArrayList<Person>();
//
//        JSONObject jsonObj;
//        try
//        {// 将json字符串转换为json对象
//            jsonObj = new JSONObject(jsonStr);
//            // 得到指定json key对象的value对象
//            JSONArray personList = jsonObj.getJSONArray("persons");
//            // 遍历jsonArray
//            for (int i = 0; i < personList.length(); i++)
//            {
//                // 获取每一个json对象
//                JSONObject jsonItem = personList.getJSONObject(i);
//                // 获取每一个json对象的值
//                Person person = new Person();
//                person.setId(jsonItem.getInt("id"));
//                person.setName(jsonItem.getString("name"));
//                person.setAddress(jsonItem.getString("address"));
//                list.add(person);
//            }
//        }
//        catch (JSONException e)
//        {
//            // TODO Auto-generated catch block
//            e.printStackTrace();
//        }
//
//        return list;
//    }

}
