import com.gargoylesoftware.htmlunit.WebClient;
import org.junit.Before;
import org.junit.Test;

public class TestBugWebpageTest {
    private String baseUrl;

    @Before
    public void setUp() throws Exception {
        baseUrl = System.getenv("M2TEST6_BASE_URL");
        if (baseUrl == null) {
            baseUrl = "http://php-facky.rhcloud.com/markattaks-tmp/website";
        }
    }

    @Test(expected = com.gargoylesoftware.htmlunit.FailingHttpStatusCodeException.class)
    public void testGET() throws Exception {
        try (WebClient webClient = new WebClient()) {
            webClient.getPage(baseUrl + "/testbug.php");
        }
    }

}
