import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.InputStreamReader;
import java.io.Reader;
import java.io.UnsupportedEncodingException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Types;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class ParticipantsImport {
	private static final String dbUrl = "jdbc:mysql://tamere.ch/db_2013";
	static int fail = 0;
	
	public static void main(String[] args) {
		try { 
			Connection sql = DriverManager.getConnection(dbUrl, "dias2013", "pd.juif");
			
			Reader reader = new InputStreamReader(new FileInputStream("participants.csv"), "ISO-8859-1");
			Scanner in = new Scanner(reader);
			
			// Skip column title
			in.nextLine();
			
			int counter = 1;
			PreparedStatement stmt = sql.prepareStatement("INSERT INTO PARTICIPANTS (PID, AID, CID, GID, SID) VALUES (?, ?, ?, ?, ?)");
			while(in.hasNext()) {
				String line = in.nextLine();
				
				int aid = -1;
				int cid = -1;
				int gid = -1;
				int sid = -1;
				
				List<String> list = new ArrayList<String>();
				Matcher m = Pattern.compile("([^\"][^,]*|\".+?\"),*").matcher(line);
				while (m.find())
				    list.add(m.group(1));
				
				String[] fields = new String[list.size()];
				int i = 0;
				for (String s : list) {
					fields[i++] = s;
				}
				
				if (fields.length < 4) {
					fail++;
					continue;
				}
				
				aid = MedalsImport.getAID(fields[0]);
				
				if (fields[1].equals("")) {
					cid = 0;
				} else {
					cid = GamesImport.getCID(fields[1]);
				}
				
				if (fields[2].equals("")) {
					gid = -1;
				} else {
					String[] gameData = fields[2].split(" ");
					try {
						gid = MedalsImport.getGID(Integer.parseInt(gameData[0]), gameData[1]);
					} catch (NumberFormatException e) {
						fail++;
						continue;
					}
				}
				sid = DisciplinesImport.getSID(fields[3]);
				
				//System.out.println(line + " : " + aid + " " + cid + " " + gid + " " + sid);
				
				if (aid != -1 && cid != -1 && gid != -1 && sid != -1) {
					try {
						stmt.setInt(1, counter);
						stmt.setInt(2, aid);
						if (cid == 0) {
							stmt.setNull(3, Types.INTEGER);
						} else {
							stmt.setInt(3, cid);
						}
						stmt.setInt(4, gid);
						stmt.setInt(5, sid);
						stmt.executeUpdate();
						
						counter++;
					} catch (Exception e) {
						fail++;
					}
				} else {
					fail++;
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
}
