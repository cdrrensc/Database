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

public class GamesImport {
	private static final String dbUrl = "jdbc:mysql://tamere.ch/db_2013";
	
	public static void main(String[] args) {
		try { 
			Connection sql = DriverManager.getConnection(dbUrl, "dias2013", "pd.juif");
			
			Reader reader = new InputStreamReader(new FileInputStream("games.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			
			// Skip column title
			in.nextLine();
			
			int counter = 1;
			
			PreparedStatement insert = sql.prepareStatement("INSERT INTO GAMES (GID, YEAR, TYPE, HOST_CITY, CID) VALUES (?, ?, ?, ?, ?)");
			
			while(in.hasNext()) {
				String line = in.nextLine();
				
				String[] fields = line.split(",");
				
				String[] data = fields[0].split(" ");
				
				System.out.println(counter + " " + data[0] + " " + data[1] + " " + fields[4] + " " + getCID(fields[5]));
				insert.setInt(1, counter);
				insert.setInt(2, Integer.parseInt(data[0]));
				insert.setString(3, data[1]);
				insert.setString(4, fields[4]);
				insert.setInt(5, getCID(fields[5]));
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
	
	public static int getCID(String name) {
		try {
			int counter = 1;
			Reader reader = new InputStreamReader(new FileInputStream("countries.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			in.nextLine();
			
			while(in.hasNext()) {
				String[] line = in.nextLine().split(",");
				
				if(line[0].equals(name)) {
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
