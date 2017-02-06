# Strings Treatment in QCubed

*This direcotry contains documents describing methods used for working with **string** in QCubed.*
 
 ## QString Class
 
 QCubed is an object-oriented framework. **QString** class contains methods designed for working with string. These methods are `public static` and the class itself is an `abstract class` and thus cannot be instantiated. The methods in this class are described below.
  
  **NOTE**: Remember to check the class out and read the comments to gain more understanding about what we do.
 
## Methods in QString 

- `FirstCharacter`: Gets first character of a supplied string. This method can operate on multi-byte encodings, of which it considers **UTF-8** as default (default encoding is set in the configuration file using `__QAPPLICATION_ENCODING_TYPE__` named constant).
- `LastCharacter`: Gets the last character of a supplied string. Multi-byte encoding is similar to that of `FirstCharacter` method. 
- `StartsWith`: Checks if a string starts with another sub-string.
- `EndsWith`: Checks if a string ends with another sub-string.
- `Truncate`: Truncates the string to a given length, adding elipses (if needed).
- `LongestCommonSubsequence`: Finds longest substring which is common among two strings. Can be used for analysing differences between two strings.
- `Base64UrlSafeEncode`: Returns Base64 encoded string for a given data which can be used safely as part of URLs and HTML forms. **More about this function has been described below.**
- `Base64UrlSafeDecode`: Decodes string encoded by `Base64UrlSafeEncode`.

### Why `Base64UrlSafeEncode`

If you do not know about base64, read a primer on Wikipedia: [https://en.wikipedia.org/wiki/Base64](https://en.wikipedia.org/wiki/Base64)

You might wonder why QCubed contains a method for base64 encoding (and one for decoding) when PHP already provides one? 

Let us look at Base64 character set. Base64 encoded string may contain 

- A-Z (capital A to capital Z)
- a-z (small A to small Z)
- 0-9 (digits)
- Characters +, / and = (`+` and `/` form part of encoded data while `=` can act as padding at the end)

The special characters `+`, `/` and `=` have special values in URLs and HTML form submissions: 
 
 - `+` converts into a single blank space
 - `/` is directory separator in URLs
 - `=` is a key-value separator/delimiter in URL encoded data (in both GET and POST requests)
 
 Hence a raw base64 encoded string may not be safe for all kinds of uses. `Base64UrlSafeEncode` replaces
 
 - `+` with `-`
 - `/` with '_'
 - `=` with blank string (since `=` is for padding anyways)
 
 This replacement makes the resulting string safe for use in URLs and HTML form submissions, which is the aim of `Base64UrlSafeEncode` method.
