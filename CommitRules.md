# Commit Rules #

These parameters must be met before committing to the trunk.

### Unit Tests ###

  * If new code was created, unit tests must be created for that code.
  * All unit tests pertaining to the changed code must be tested, and passed.

### PHPDoc Headers/Comments ###

  * Valid PHPDoc comments must be created for any files, classes, public class properties/constants, or public methods.
  * PHPDoc comments are optional for protected methods/properties.
  * Valid PHPDoc commets must be created for any code changed that did not already have them.
  * PHPDoc comments must be updated to reflect any changes made.

### Comments ###

  * Private class variables can have a single line comment or block comment if necessary.
  * Be **rigorous** about keeping comments up to date.
  * Commented-out code is a no-no.  Either delete it, or (fix and?) integrate it.

### Log Message ###

  * Prefix your message with the class/folder your changes apply to enclosed in square brackets.  For example: "_[[A\_Db\_Recordset\_MySQL](A_Db_Recordset_MySQL.md)] Did something blah blah..._", or "_[[A\_Db](A_Db.md)] Did something else..._".
  * Provide a thorough description of what changes were made.  Separate items with new lines.
  * Avoid committing changes to more than one class at a time, the exception being if the changes depend on each other.

### Syntax Standards ###

At this point, the PEAR Codesniffer standard to check against has not been yet decided.  ~~Until then, check with Zend, and ignore any line length or tab errors~~ Refer to this document: SyntaxStandards .