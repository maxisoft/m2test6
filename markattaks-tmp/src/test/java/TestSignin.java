import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.Page;
import com.gargoylesoftware.htmlunit.WebClient;
import com.gargoylesoftware.htmlunit.html.*;
import org.junit.After;
import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

import java.net.URL;

public class TestSignin {
    public static final String USER = "student";
    public static final String PASSWORD = "banana";
    public static final String WRONG_PASSWORD = "apple";
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
        }
        else {
            webClient = new WebClient(BrowserVersion.CHROME);
        }
        webClient.getPage(baseUrl + "/db/bootstrap/");
    }

    @After
    public void tearDown() {
        webClient.close();
    }

    private HtmlPage login(String user, String password) throws java.io.IOException {
        HtmlPage page = webClient.getPage(baseUrl + "/signin.php");
        // Get the form that we are dealing with and within that form,
        // find the submit button and the field that we want to change.
        final HtmlForm form = page.getFormByName("signin");

        final HtmlButton button = form.getButtonByName("submitbutton");
        final HtmlTextInput loginField = form.getInputByName("login");
        final HtmlPasswordInput passwordField = form.getInputByName("password");

        // Change the value of the text field
        loginField.setValueAttribute(user);
        passwordField.setValueAttribute(password);

        // Now submit the form by clicking the button and get back the second page.
        return button.click();
    }

    @Test
    public void testGET() throws Exception {
        Page page = webClient.getPage(baseUrl + "/signin.php");
        Assert.assertEquals("text/html", page.getWebResponse().getContentType());
    }

    @Test
    public void testLogin() throws Exception {
        final HtmlPage nextPage = login(USER, PASSWORD);
        Assert.assertEquals(new URL(baseUrl + "/"), nextPage.getUrl());
        HtmlSpan span = nextPage.getHtmlElementById("greetingUserName");
        Assert.assertEquals(USER, span.getTextContent().toLowerCase().trim());
    }

    @Test
    public void testFailLogin() throws Exception {
        final HtmlPage nextPage = login(USER, WRONG_PASSWORD);

        Assert.assertEquals(new URL(baseUrl + "/signin.php"), nextPage.getUrl());
        HtmlDivision div = nextPage.getHtmlElementById("loginError");
        Assert.assertTrue("Must notice that login was bad", div.getTextContent().toLowerCase().contains("bad login"));
    }

    @Test
    public void testTryToLoginWhileLogged() throws Exception {
        testLogin();
        HtmlPage page = webClient.getPage(baseUrl + "/signin.php");
        Assert.assertEquals(new URL(baseUrl + "/"), page.getUrl());
    }

}
