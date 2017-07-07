#QBaseClass
This class is the base for all QCubed classes. It primarily throws exceptions when you try to call a function or access a property that does not exist in the class.

It also has the following method:

```public final function OverrideAttributes($mixOverrideArray)```

Which allows you to set any property by supplying a key->value array. The primary use of this is from a control or form template, and since these do not currently have a common subclass, this is the closest parent class to include this functionality. This might be better off as a trait?
