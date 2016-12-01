<?php
/*
 * AvaTax Enum Class
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com>
 */class ErrorCodeId extends AvaTaxEnum 
{

    const ServerConfiguration = "ServerConfiguration";
    const AccountInvalidException = "AccountInvalidException";
    const CompanyInvalidException = "CompanyInvalidException";
    const EntityNotFoundError = "EntityNotFoundError";
    const ValueRequiredError = "ValueRequiredError";
    const RangeError = "RangeError";
    const RangeCompareError = "RangeCompareError";
    const RangeSetError = "RangeSetError";
    const TaxpayerNumberRequired = "TaxpayerNumberRequired";
    const CommonPassword = "CommonPassword";
    const WeakPassword = "WeakPassword";
    const StringLengthError = "StringLengthError";
    const EmailValidationError = "EmailValidationError";
    const EmailMissingError = "EmailMissingError";
    const ParserFieldNameError = "ParserFieldNameError";
    const ParserFieldValueError = "ParserFieldValueError";
    const ParserSyntaxError = "ParserSyntaxError";
    const ParserTooManyParametersError = "ParserTooManyParametersError";
    const ParserUnterminatedValueError = "ParserUnterminatedValueError";
    const DeleteUserSelfError = "DeleteUserSelfError";
    const OldPasswordInvalid = "OldPasswordInvalid";
    const CannotChangePassword = "CannotChangePassword";
    const CannotChangeCompanyCode = "CannotChangeCompanyCode";
    const AuthenticationException = "AuthenticationException";
    const AuthorizationException = "AuthorizationException";
    const ValidationException = "ValidationException";
    const InactiveUserError = "InactiveUserError";
    const AuthenticationIncomplete = "AuthenticationIncomplete";
    const BasicAuthIncorrect = "BasicAuthIncorrect";
    const IdentityServerError = "IdentityServerError";
    const BearerTokenInvalid = "BearerTokenInvalid";
    const ModelRequiredException = "ModelRequiredException";
    const AccountExpiredException = "AccountExpiredException";
    const VisibilityError = "VisibilityError";
    const BearerTokenNotSupported = "BearerTokenNotSupported";
    const InvalidSecurityRole = "InvalidSecurityRole";
    const InvalidRegistrarAction = "InvalidRegistrarAction";
    const RemoteServerError = "RemoteServerError";
    const NoFilterCriteriaException = "NoFilterCriteriaException";
    const OpenClauseException = "OpenClauseException";
    const JsonFormatError = "JsonFormatError";
    const UnhandledException = "UnhandledException";
    const ReportingCompanyMustHaveContactsError = "ReportingCompanyMustHaveContactsError";
    const CompanyProfileNotSet = "CompanyProfileNotSet";
    const ModelStateInvalid = "ModelStateInvalid";
    const DateRangeError = "DateRangeError";
    const InvalidDateRangeError = "InvalidDateRangeError";
    const DeleteInformation = "DeleteInformation";
    const CannotCreateDeletedObjects = "CannotCreateDeletedObjects";
    const CannotModifyDeletedObjects = "CannotModifyDeletedObjects";
    const ReturnNameNotFound = "ReturnNameNotFound";
    const InvalidAddressTypeAndCategory = "InvalidAddressTypeAndCategory";
    const DefaultCompanyLocation = "DefaultCompanyLocation";
    const InvalidCountry = "InvalidCountry";
    const InvalidCountryRegion = "InvalidCountryRegion";
    const BrazilValidationError = "BrazilValidationError";
    const BrazilExemptValidationError = "BrazilExemptValidationError";
    const BrazilPisCofinsError = "BrazilPisCofinsError";
    const JurisdictionNotFoundError = "JurisdictionNotFoundError";
    const MedicalExciseError = "MedicalExciseError";
    const RateDependsTaxabilityError = "RateDependsTaxabilityError";
    const RateDependsEuropeError = "RateDependsEuropeError";
    const RateTypeNotSupported = "RateTypeNotSupported";
    const CannotUpdateNestedObjects = "CannotUpdateNestedObjects";
    const UPCCodeInvalidChars = "UPCCodeInvalidChars";
    const UPCCodeInvalidLength = "UPCCodeInvalidLength";
    const IncorrectPathError = "IncorrectPathError";
    const InvalidJurisdictionType = "InvalidJurisdictionType";
    const MustConfirmResetLicenseKey = "MustConfirmResetLicenseKey";
    const DuplicateCompanyCode = "DuplicateCompanyCode";
    const TINFormatError = "TINFormatError";
    const DuplicateNexusError = "DuplicateNexusError";
    const UnknownNexusError = "UnknownNexusError";
    const ParentNexusNotFound = "ParentNexusNotFound";
    const InvalidTaxCodeType = "InvalidTaxCodeType";
    const CannotActivateCompany = "CannotActivateCompany";
    const DuplicateEntityProperty = "DuplicateEntityProperty";
    const BatchSalesAuditMustBeZippedError = "BatchSalesAuditMustBeZippedError";
    const BatchZipMustContainOneFileError = "BatchZipMustContainOneFileError";
    const BatchInvalidFileTypeError = "BatchInvalidFileTypeError";
    const PointOfSaleFileSize = "PointOfSaleFileSize";
    const PointOfSaleSetup = "PointOfSaleSetup";
    const GetTaxError = "GetTaxError";
    const AddressConflictException = "AddressConflictException";
    const DocumentCodeConflict = "DocumentCodeConflict";
    const MissingAddress = "MissingAddress";
    const InvalidParameter = "InvalidParameter";
    const InvalidParameterValue = "InvalidParameterValue";
    const CompanyCodeConflict = "CompanyCodeConflict";
    const BadDocumentFetch = "BadDocumentFetch";
    const ServerUnreachable = "ServerUnreachable";
    const SubscriptionRequired = "SubscriptionRequired";
}
