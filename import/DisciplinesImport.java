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

public class DisciplinesImport {
	private static final String dbUrl = "jdbc:mysql://tamere.ch/db_2013";
	
	public static void main(String[] args) {
		try { 
			Connection sql = DriverManager.getConnection(dbUrl, "dias2013", "pd.juif");
			
			Reader reader = new InputStreamReader(new FileInputStream("Disciplines.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			
			// Skip column title
			in.nextLine();
			
			int counter = 1;
			
			PreparedStatement insert = sql.prepareStatement("INSERT INTO DISCIPLINES (DID, NAME, SID) VALUES (?, ?, ?)");
			
			while(in.hasNext()) {
				String line = in.nextLine();
				
				String[] fields = line.split(",");
				
				//System.out.println(counter + " " + fields[0] + " \t[" + fields[1] + "] - " + getSID(fields[1]));
				insert.setInt(1, counter);
				insert.setString(2, fields[0]);
				insert.setInt(3, getSID(fields[1]));
				insert.executeUpdate();
				
					
				counter++;
			}
			
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
	
	public static int getSID(String name) {
		try {
			int counter = 1;
			Reader reader = new InputStreamReader(new FileInputStream("sports.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			in.nextLine();
			
			while(in.hasNext()) {
				String line = in.nextLine().trim();
				
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
		return -132;
	}
}
