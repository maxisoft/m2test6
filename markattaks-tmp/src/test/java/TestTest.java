import org.junit.*;

import static org.junit.Assert.*;

/**
 * Created by duboi on 19/12/2015.
 */
public class TestTest {
    private static final String s = "TEST";
    private Test test;

    @Before
    public void setUp() throws Exception {
        test = new Test(s);
    }

    @org.junit.Test
    public void testGetS() throws Exception {
        Assert.assertEquals(s, test.getS());
    }
}