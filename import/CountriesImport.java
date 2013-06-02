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

public class CountriesImport {
	private static final String dbUrl = "jdbc:mysql://tamere.ch/db_2013";
	
	public static void main(String[] args) {
		try { 
			Connection sql = DriverManager.getConnection(dbUrl, "dias2013", "pd.juif");
			
			Reader reader = new InputStreamReader(new FileInputStream("countries.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			
			// Skip column title
			in.nextLine();
			
			int counterCountry = 1;
			int counterIOC = 1;
			
			PreparedStatement insertCountry = sql.prepareStatement("INSERT INTO COUNTRIES (CID, NAME) VALUES (?, ?)");
			PreparedStatement insertIOC = sql.prepareStatement("INSERT INTO IOC_CODE (ICID, CODE, CID) VALUES (?, ?, ?)");
			while(in.hasNext()) {
				String line = in.nextLine().replace("\"", "");
				
				String[] data = line.split(",");
				
				//System.out.print(counter + " " + data[0] + " ");
				insertCountry.setInt(1, counterCountry);
				insertCountry.setString(2, data[0]);
				insertCountry.executeUpdate();
				
				if (data.length > 1) {
					insertIOC.setInt(1, counterIOC);
					insertIOC.setString(2, data[1]);
					insertIOC.setInt(3, counterCountry);
					insertIOC.executeUpdate();
					counterIOC++;
				}
				//System.out.println();
				
				/*System.out.println(counter + " : " + name);
				if(counter > 1)
				System.exit(0);*/
				
				
				/*stmt.setInt(1, counter);
				stmt.setString(2, name);
				stmt.executeUpdate();*/
				
				counterCountry++;
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
