# AvaTaxClientLibrary
Client libraries for AvaTax REST v2

This repository contains the open source implementation of a standard client library for AvaTax.  For more information, please see https://developer.avalara.com .

Online swagger documentation is always available at https://sandbox-rest.avatax.com/swagger/ui/index.html .

# REST v2 SDK Library Status

Avalara maintains and publishes SDKs for our current REST v2 API for the following programming languages:

| Client | Version | Status | GitHub |
|--------|---------|--------|--------|
| C# | [![NuGet](https://img.shields.io/nuget/v/Avalara.AvaTax.svg?style=plastic)](https://www.nuget.org/packages/Avalara.AvaTax/) | [![Travis](https://api.travis-ci.org/avadev/AvaTax-REST-V2-DotNet-SDK.svg?branch=master&style=plastic)](https://travis-ci.org/avadev/AvaTax-REST-V2-DotNet-SDK) | [AvaTax-REST-V2-DotNet-SDK](https://github.com/avadev/AvaTax-REST-V2-DotNet-SDK) |
| Java | [![Maven Central](https://maven-badges.herokuapp.com/maven-central/net.avalara.avatax/avatax-rest-v2-api-java_2.11/badge.svg?style=plastic)](https://maven-badges.herokuapp.com/maven-central/net.avalara.avatax/avatax-rest-v2-api-java_2.11) [![Sonatype Snapshots](https://img.shields.io/badge/Sonatype%20Snapshots-2.17.3.48--SNAPSHOT-blue.svg?style=plastic)](https://oss.sonatype.org/#nexus-search;gav~net.avalara.avatax~avatax-rest-v2-api-java_2.11~2.17.3.48-SNAPSHOT~~) | [![Travis](https://api.travis-ci.org/avadev/AvaTax-REST-V2-JRE-SDK.svg?branch=master&style=plastic)](https://travis-ci.org/avadev/AvaTax-REST-V2-JRE-SDK) | [AvaTax-REST-V2-JRE-SDK](https://github.com/avadev/AvaTax-REST-V2-JRE-SDK) |
| JavaScript | [![npm](https://img.shields.io/npm/v/avatax.svg?style=plastic)](https://www.npmjs.com/package/avatax) | [![Travis](https://api.travis-ci.org/avadev/AvaTax-REST-V2-JRE-SDK.svg?branch=master&style=plastic)](https://travis-ci.org/avadev/AvaTax-REST-V2-JS-SDK) | [AvaTax-REST-V2-JS-SDK](https://github.com/avadev/AvaTax-REST-V2-JS-SDK) |
| PHP | [![Packagist](https://img.shields.io/packagist/v/avalara/avataxclient.svg?style=plastic)](https://packagist.org/packages/avalara/avataxclient) | [![Travis](https://api.travis-ci.org/avadev/AvaTax-REST-V2-PHP-SDK.svg?branch=master&style=plastic)](https://travis-ci.org/avadev/AvaTax-REST-V2-PHP-SDK) | [AvaTax-REST-V2-PHP-SDK](https://github.com/avadev/AvaTax-REST-V2-PHP-SDK) |
| Scala | [![Maven Central](https://maven-badges.herokuapp.com/maven-central/net.avalara.avatax/avatax-rest-v2-api-java_2.11/badge.svg?style=plastic)](https://maven-badges.herokuapp.com/maven-central/net.avalara.avatax/avatax-rest-v2-api-java_2.11) [![Sonatype Snapshots](https://img.shields.io/badge/Sonatype%20Snapshots-2.17.3.48--SNAPSHOT-blue.svg?style=plastic)](https://oss.sonatype.org/#nexus-search;gav~net.avalara.avatax~avatax-rest-v2-api-java_2.11~2.17.3.48-SNAPSHOT~~) | [![Travis](https://api.travis-ci.org/avadev/AvaTax-REST-V2-JRE-SDK.svg?branch=master&style=plastic)](https://travis-ci.org/avadev/AvaTax-REST-V2-JRE-SDK)  | [AvaTax-REST-V2-JRE-SDK](https://github.com/avadev/AvaTax-REST-V2-JRE-SDK) |
| Ruby | [![Ruby Gem](https://img.shields.io/gem/v/avatax.svg?style=plastic)](https://rubygems.org/gems/avatax) | [![Travis](https://api.travis-ci.org/avadev/AvaTax-REST-V2-Ruby-SDK.svg?branch=master&style=plastic)](https://travis-ci.org/avadev/AvaTax-REST-V2-Ruby-SDK) | [AvaTax-REST-V2-Ruby-SDK](https://github.com/avadev/AvaTax-REST-V2-Ruby-SDK) |
| IBMi RPG | | | [AvaTax-REST-V2-RPGLE-SDK](https://github.com/avadev/AvaTax-REST-V2-RPGLE-SDK) |
| SalesForce Apex | | | [AvaTax-REST-V2-Apex-SDK](https://github.com/avadev/AvaTax-REST-V2-Apex-SDK) |

# SOAP v1 SDK Library Status

The AvaTax SOAP SDK exists for backward compatibility with older software.  Users are encouraged to upgrade to the REST v2 SDK when possible.

| Client | Package Manager | GitHub |
|--------|---------|--------|
| C# | [![NuGet](https://img.shields.io/nuget/v/Avalara.AvaTax.SoapClient.svg?style=plastic)](https://www.nuget.org/packages/Avalara.AvaTax.SoapClient/) | [AvaTax-Calc-AccountSvc-SOAP-csharp](https://github.com/avadev/AvaTax-Calc-AccountSvc-SOAP-csharp) |
| Java | n/a | [AvaTax-SOAP-Java-SDK](https://github.com/avadev/AvaTax-SOAP-Java-SDK) |
| PHP | [![Packagist](https://img.shields.io/packagist/v/avalara/avatax.svg?style=plastic)](https://packagist.org/packages/avalara/avatax) | [AvaTax-Calc-SOAP-PHP](https://github.com/avadev/AvaTax-Calc-SOAP-PHP) |
| PHP | n/a | [AvaTax-Calc-REST-PHP](https://github.com/avadev/AvaTax-Calc-REST-PHP) |
| PHP | n/a | [AvaTax-SOAP-PHP-SDK](https://github.com/avadev/AvaTax-SOAP-PHP-SDK) |
| Ruby | n/a | [AvaTax-Calc-REST-Ruby](https://github.com/avadev/AvaTax-Calc-REST-Ruby) |
| IBMi RPG | n/a | [AvaTax-Calc-SOAP-IBMi](https://github.com/avadev/AvaTax-Calc-SOAP-IBMi) |
| Salesforce Apex | n/a | [AvaTax-SOAP-SF-SDK](https://github.com/avadev/AvaTax-SOAP-SF-SDK) |
| CURL | n/a | [AvaTax-Calc-REST-cURL](https://github.com/avadev/AvaTax-Calc-REST-cURL) |
| JavaScript | n/a | [AvaTax-Calc-REST-JavaScript](https://github.com/avadev/AvaTax-Calc-REST-JavaScript) |
| iOS | n/a | [AvaTax-Calc-SDK-iOS](https://github.com/avadev/AvaTax-Calc-SDK-iOS) |
| C++ | n/a | [AvaTax-Calc-SOAP-CPP](https://github.com/avadev/AvaTax-Calc-SOAP-CPP) |
| Python | n/a | [AvaTax-Calc-REST-Python](https://github.com/avadev/AvaTax-Calc-REST-Python) |
