import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.ObjectInputStream;
import java.io.Reader;
import java.io.UnsupportedEncodingException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Types;
import java.util.Scanner;

public class MedalsImport {
	private static final String dbUrl = "jdbc:mysql://tamere.ch/db_2013";
	
	
	public static void main(String[] args) {
		try { 
			Connection sql = DriverManager.getConnection(dbUrl, "dias2013", "pd.juif");
			
			Reader reader = new InputStreamReader(new FileInputStream("medals.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			
			// Skip column title
			in.nextLine();
			
			int tid = 0;
			int counter = 1;
			int fail = 0;
			
			PreparedStatement insert = sql.prepareStatement("INSERT INTO MEDALS (MID, TYPE, CID, EID, AID, TID) VALUES (?, ?, ?, ?, ?, ?)");
			
			Events eList = null;
			try {
				ObjectInputStream is = new ObjectInputStream(new FileInputStream(new File("events.obj")));
				eList = (Events)is.readObject();
				is.close();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (ClassNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} 
			
			while(in.hasNext()) {
				String line = in.nextLine();
				
				String[] fields = line.split(",");
				
				int cid = -1;
				int eid = -1;
				int aid = -1;
				String type = "";
				
				if (fields[0].equals("")) {
					cid = 0;
				} else {
					cid = GamesImport.getCID(fields[0]);
				}
				
				type = fields[2].split(" ")[0];
				
				eid = eList.get(fields[1]);
				
				String[] athletes = fields[3].split(";");
				
				for (String a : athletes) {
					aid = getAID(a);
					
					//System.out.println(counter + " " + aid + " " + cid + " " + type + " " + eid);
					
					if (cid != -1 && eid != -1 && aid != -1 && !type.equals("")) {
						try {
							insert.setInt(1, counter);
							insert.setString(2, type);
							if (cid == 0) {
								insert.setNull(3, Types.INTEGER);
							} else {
								insert.setInt(3, cid);
							}
							insert.setInt(4, eid);
							insert.setInt(5, aid);
							if (athletes.length == 1) {
								insert.setNull(6, Types.INTEGER);
							} else {
								insert.setInt(6, tid);
							}
							insert.executeUpdate();
						} catch (Exception e) {
							fail++;
						}
						
						counter++;
					} else {
						fail++;
					}
				}
				if (athletes.length != 1) {
					tid++;
				}
			}
			
			System.out.println(fail);
			
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
	
	public static int getAID(String name) {
		try {
			int counter = 1;
			Reader reader = new InputStreamReader(new FileInputStream("athlete.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			in.nextLine();
			
			while(in.hasNext()) {
				String line = in.nextLine();
				
				if(line.equals(name)) {
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
