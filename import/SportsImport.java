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

public class SportsImport {
	private static final String dbUrl = "jdbc:mysql://tamere.ch/db_2013";
	
	public static void main(String[] args) {
		try { 
			Connection sql = DriverManager.getConnection(dbUrl, "dias2013", "pd.juif");
			
			Reader reader = new InputStreamReader(new FileInputStream("sports.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			
			// Skip column title
			in.nextLine();
			
			int counter = 1;
			
			PreparedStatement insert = sql.prepareStatement("INSERT INTO SPORTS (SID, NAME) VALUES (?, ?)");
			
			while(in.hasNext()) {
				String line = in.nextLine();
				
				insert.setInt(1, counter);
				insert.setString(2, line);
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
}
