package net.avalara.avatax.rest.client;

import com.google.gson.reflect.TypeToken;
import net.avalara.avatax.rest.client.models.*;
import net.avalara.avatax.rest.client.enums.*;

import org.apache.commons.codec.binary.Base64;

import java.math.BigDecimal;
import java.util.Date;
import java.util.HashMap;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;
import java.util.ArrayList;

/*
 * AvaTax Software Development Kit for Java JRE based environments
 *
 * (c) 2004-2017 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author     Dustin Welden <dustin.welden@@avalara.com>
 * @@copyright  2004-2017 Avalara, Inc.
 * @@license    https://www.apache.org/licenses/LICENSE-2.0
 * @@link       https://github.com/avadev/AvaTax-REST-V2-JRE-SDK
 */
 
public class AvaTaxClient {

    private final ExecutorService threadPool;
    private RestCallFactory restCallFactory;

    private AvaTaxClient() {
        this(null);
    }

    private AvaTaxClient(ExecutorService threadPool) {
        if (threadPool != null) {
            this.threadPool = threadPool;
        } else {
            this.threadPool = Executors.newFixedThreadPool(3);
        }
    }

    public AvaTaxClient(String appName, String appVersion, String machineName, AvaTaxEnvironment environment) {
        this(appName, appVersion, machineName, environment == AvaTaxEnvironment.Production ? AvaTaxConstants.Production_Url : AvaTaxConstants.Sandbox_Url);
    }

    public AvaTaxClient(String appName, String appVersion, String machineName, String environmentUrl) {
        this();
        this.restCallFactory = new RestCallFactory(appName, appVersion, machineName, environmentUrl);
    }

    public AvaTaxClient(String appName, String appVersion, String machineName, AvaTaxEnvironment environment, String proxyHost, int proxyPort, String proxySchema) {
        this(appName, appVersion, machineName, environment == AvaTaxEnvironment.Production ? AvaTaxConstants.Production_Url : AvaTaxConstants.Sandbox_Url, proxyHost, proxyPort, proxySchema);
    }

    public AvaTaxClient(String appName, String appVersion, String machineName, String environmentUrl, String proxyHost, int proxyPort, String proxySchema) {
        this();
        this.restCallFactory = new RestCallFactory(appName, appVersion, machineName, environmentUrl, proxyHost, proxyPort, proxySchema);
    }


    public AvaTaxClient withSecurity(String securityHeader) {
        this.restCallFactory.addSecurityHeader(securityHeader);

        return this;
    }

    public AvaTaxClient withSecurity(String username, String password) {
        String header = null;

        try {
            header = Base64.encodeBase64String((username + ":" + password).getBytes("utf-8"));
        } catch (java.io.UnsupportedEncodingException ex) {
            System.out.println("Could not find encoding for UTF-8.");
            ex.printStackTrace();
        }

        return withSecurity(header);
    }


//region Methods
@foreach(var m in SwaggerModel.Methods) {
    Write(JavadocComment(m, 4));
    Write("    public " + JavaTypeName(m.ResponseTypeName) + " " + FirstCharLower(m.Name) + "(");

    bool any = false;
    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        Write(JavaTypeName(p.TypeName) + " " + p.CleanParamName + ", ");
        any = true;
    }
    if (any) {
        Backtrack(2);
    }

    WriteLine(") throws Exception {");
    WriteLine("        AvaTaxPath path = new AvaTaxPath(\"" + m.URI + "\");");
    foreach (var p in m.Params) {
        if (p.ParameterLocation == ParameterLocationType.UriPath) {
            WriteLine("        path.applyField(\"{0}\", {1});", p.ParamName, p.CleanParamName);
        } else if (p.ParameterLocation == ParameterLocationType.QueryString) {
            WriteLine("        path.addQuery(\"{0}\", {1});", p.ParamName, p.CleanParamName);
        }
    }
    
    if (m.ResponseTypeName == "String") {
        WriteLine("        return ((RestCall<" + JavaTypeName(m.ResponseTypeName) + ">)restCallFactory.createRestCall(\"" + m.HttpVerb + "\", path, " + (m.BodyParam == null ? "null" : "model") + ", new TypeToken<" + JavaTypeName(m.ResponseTypeName) + ">(){})).call();");
    } else if (m.ResponseTypeName == "FileResult") {
        WriteLine("        return ((RestCall<" + JavaTypeName(m.ResponseTypeName) + ">)restCallFactory.createRestCall(\"" + m.HttpVerb + "\", path, " + (m.BodyParam == null ? "null" : "model") + ", new TypeToken<" + JavaTypeName(m.ResponseTypeName) + ">(){})).call();");
    } else {
        WriteLine("        return ((RestCall<" + JavaTypeName(m.ResponseTypeName) + ">)restCallFactory.createRestCall(\"" + m.HttpVerb + "\", path, " + (m.BodyParam == null ? "null" : "model") + ", new TypeToken<" + JavaTypeName(m.ResponseTypeName) + ">(){})).call();");
    }

    WriteLine("    }");
    WriteLine("");
    
    // Async version of the same API
    Write(JavadocComment(m, 4));
    Write("    public Future<" + JavaTypeName(m.ResponseTypeName) + "> " + FirstCharLower(m.Name) + "Async(");

    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        Write(JavaTypeName(p.TypeName) + " " + p.CleanParamName + ", ");
    }
    if (any) {
        Backtrack(2);
    }

    WriteLine(") {");
    WriteLine("        AvaTaxPath path = new AvaTaxPath(\"" + m.URI + "\");");
    foreach (var p in m.Params) {
        if (p.ParameterLocation == ParameterLocationType.UriPath) {
            WriteLine("        path.applyField(\"{0}\", {1});", p.ParamName, p.CleanParamName);
        } else if (p.ParameterLocation == ParameterLocationType.QueryString) {
            WriteLine("        path.addQuery(\"{0}\", {1});", p.ParamName, p.CleanParamName);
        }
    }
    
    if (m.ResponseTypeName == "String") {
        WriteLine("        return this.threadPool.submit((RestCall<" + JavaTypeName(m.ResponseTypeName) + ">)restCallFactory.createRestCall(\"" + m.HttpVerb + "\", path, " + (m.BodyParam == null ? "null" : "model") + ", new TypeToken<" + JavaTypeName(m.ResponseTypeName) + ">(){}));");
    } else if (m.ResponseTypeName == "FileResult") {
        WriteLine("        return this.threadPool.submit((RestCall<" + JavaTypeName(m.ResponseTypeName) + ">)restCallFactory.createRestCall(\"" + m.HttpVerb + "\", path, " + (m.BodyParam == null ? "null" : "model") + ", new TypeToken<" + JavaTypeName(m.ResponseTypeName) + ">(){}));");
    } else {
        WriteLine("        return this.threadPool.submit((RestCall<" + JavaTypeName(m.ResponseTypeName) + ">)restCallFactory.createRestCall(\"" + m.HttpVerb + "\", path, " + (m.BodyParam == null ? "null" : "model") + ", new TypeToken<" + JavaTypeName(m.ResponseTypeName) + ">(){}));");
    }

    WriteLine("    }");
    WriteLine("");
}
//endregion

}
    