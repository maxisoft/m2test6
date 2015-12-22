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
            baseUrl = "https://php-facky.rhcloud.com/markattaks-tmp/website";
        }
        String httpProxyHost = System.getenv("https.proxyHost");
        if (httpProxyHost != null) {
            int httpProxyPort = Integer.parseInt(System.getenv("https.proxyPort"));
            webClient = new WebClient(BrowserVersion.getDefault(), httpProxyHost, httpProxyPort);
        } else if (System.getenv("JENKINS_URL") != null) {//TODO try-hard
            webClient = new WebClient(BrowserVersion.getDefault(), "proxy-web.univ-fcomte.fr", 3128);
        }
        else {
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
