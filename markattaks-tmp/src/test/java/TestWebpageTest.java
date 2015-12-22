import com.gargoylesoftware.htmlunit.Page;
import com.gargoylesoftware.htmlunit.WebClient;
import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

public class TestWebpageTest {

    private String baseUrl;

    @Before
    public void setUp() throws Exception {
        baseUrl = System.getenv("M2TEST6_BASE_URL");
        if (baseUrl == null) {
            baseUrl = "http://php-facky.rhcloud.com/markattaks-tmp/website";
        }
    }

    @Test
    public void testGET() throws Exception {
        try (WebClient webClient = new WebClient()) {
            Page page = webClient.getPage(baseUrl + "/test.php");
            String content = page.getWebResponse().getContentAsString();
            Assert.assertEquals("text/plain", page.getWebResponse().getContentType());
            Assert.assertEquals("1", content);
        }

    }

}
