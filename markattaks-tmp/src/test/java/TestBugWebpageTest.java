import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.WebClient;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

public class TestBugWebpageTest {
    private String baseUrl;
    private WebClient webClient;

    @Before
    public void setUp() throws Exception {
        baseUrl = System.getenv("M2TEST6_BASE_URL");
        if (baseUrl == null) {
            baseUrl = "http://php-facky.rhcloud.com/markattaks-tmp/website";
        }
        String httpProxyHost = System.getenv("http.proxyHost");
        if (httpProxyHost != null) {
            int httpProxyPort = Integer.parseInt(System.getenv("http.proxyPort"));
            webClient = new WebClient(BrowserVersion.getDefault(), httpProxyHost, httpProxyPort);
        } else {
            webClient = new WebClient(BrowserVersion.getDefault());
        }
    }

    @After
    public void tearDown() {
        webClient.close();
    }

    @Test(expected = com.gargoylesoftware.htmlunit.FailingHttpStatusCodeException.class)
    public void testGET() throws Exception {
        webClient.getPage(baseUrl + "/testbug.php");
    }


}
