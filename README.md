## Description

Simple class autoloader for PHP

### Installation

Include `Initializer.php` in your project.
Use APP_ROOT_PATH to specify root directory to find classes from

### Use

To specify namespace mapping use JSON file `Scal.json`

#### Configuration (autoload.conf.json)

You should end both namespace and path with backslash

```json
{
  "mapping" {
    "Direct\\": "Direct\\",
    "Complex\\Direct\\": "ComplexDirect\\Complex\\",
    "Recursion\\": "Recursion\\*",
    "Many\\": [
      "Many\\Many1\\",
      "Many\\Many2\\*"
    ]
  }
}
```

## Author

Alexandr Shamanin (@slpAkkie)

## Version

2.3.1
