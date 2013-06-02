import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.InputStreamReader;
import java.io.Reader;
import java.io.UnsupportedEncodingException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.Scanner;

public class EventsImport {
	private static final String dbUrl = "jdbc:mysql://tamere.ch/db_2013";
	
	
	public static void main(String[] args) {
		try { 
			Connection sql = DriverManager.getConnection(dbUrl, "dias2013", "pd.juif");
			
			Reader reader = new InputStreamReader(new FileInputStream("events.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			
			// Skip column title
			in.nextLine();
			
			int counter = 1;
			int fail = 0;
			
			PreparedStatement insert = sql.prepareStatement("INSERT INTO EVENTS (EID, GID, DID) VALUES (?, ?, ?)");
			
			Events eList = new Events();
			while(in.hasNext()) {
				String line = in.nextLine();
				
				String[] fields = line.split(",");
				
				int did = -1;
				int gid = -1;
				
				if (fields.length >= 3) {
					String[] gameData = fields[2].split(" ");
					did = getDID(fields[1]);
					gid = getGID(Integer.parseInt(gameData[0]), gameData[1]);
				} else {
					String[] data = fields[0].split(" ");
					
					int i;
					for (i=0; i < data.length && !data[i].equals("the"); i++);
					i++;
					if (i >= data.length) {
						System.out.println("FUCK : " + line);
						System.exit(0);
					}
					
					int year = Integer.parseInt(data[i]);
					i++;
					String type = data[i];
					
					String discipline = "";
					if (fields.length == 2) {
						discipline = fields[1];
					} else {
						for (i=0; i < data.length && !data[i].equals("-"); i++);
						i++;
						for(int j = i; j < data.length; j++) {
							discipline += data[j] + " ";
						}
					}
					did = getDID(discipline);
					gid = getGID(year, type);
				}
				
				if (did != -1 && gid != -1) {
					try {
						insert.setInt(1, counter);
						insert.setInt(2, gid);
						insert.setInt(3, did);
						insert.executeUpdate();
						eList.add(fields[0], counter);
					} catch (Exception e) {
						fail++;
					}
					
					System.out.println(counter + " " + did + " " + gid);
					
					counter++;
				} else {
					fail++;
				}
			}
			
			System.out.println(fail);
			eList.write();
			
			sql.close();
		} catch (SQLException e) {
			e.printStackTrace();
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public static int getDID(String name) {
		try {
			int counter = 1;
			Reader reader = new InputStreamReader(new FileInputStream("Disciplines.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			in.nextLine();
			
			while(in.hasNext()) {
				String line = in.nextLine().split(",")[0].trim();
				
				if(line.equals(name.trim())) {
					in.close();
					return counter;
				}
				counter++;
			}
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return -1;
	}
	
	public static int getGID(int year, String type) {
		try {
			int counter = 1;
			Reader reader = new InputStreamReader(new FileInputStream("games.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			in.nextLine();
			
			while(in.hasNext()) {
				String line = in.nextLine().split(",")[0].trim();
				
				if(line.equals(year + " " + type + " Olympics")) {
					in.close();
					return counter;
				}
				counter++;
			}
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return -1;
	}
}
