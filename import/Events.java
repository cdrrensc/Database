import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.Serializable;
import java.util.HashMap;
import java.util.Map;


public class Events implements Serializable {
	
	private Map<String, Integer> events;
	
	public Events() {
		events = new HashMap<String, Integer>();
	}
	
	public void add(String name, int id) {
		events.put(name, id);
	}
	
	public int get(String name) {
		if (events.containsKey(name))
			return events.get(name);
		return -1;
	}
	
	public void write() {
		try {
			ObjectOutputStream os = new ObjectOutputStream(new FileOutputStream(new File("events.obj")));
			os.writeObject(this);
			os.close();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
