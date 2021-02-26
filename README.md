# Scal

Simple class autoloader from me for you. Take & Use.

## Install

Include the `Scal.php` file at the entry point of your application. Here will be the root directory for searching class

## Use

After you have included Scal.php you can customize namespace paths. To do this create a Scal.json file in the root directory (Remember that the root directory its where Scal is running).
Example of Scal.json

```json
{
  "np": {
    "Direct": "Direct",
    "Complex\\Direct": "ComplexDirect\\Complex",
    "Recursion": "Recursion\\*",
    "Many": [
      "Many\\Many1\\",
      "Many\\Many2\\*"
    ]
  }
}
```

## Configuration (Scal.json)

It's unnecessary to end namespaces with `\`
So you can use single path or an array of paths according to it.
You also can specify path as recursive for searching classes in all child directories

## Author

Alexandr Shamanin (@slpAkkie)

## Version

2.1.0
