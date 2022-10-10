bu# Dump 'here'
Dump your current line of code, for make sure your on right tract.

# Installation
```bash
composer require sonypradana/here  --dev
```
# How to Use
Put `here()` anywhere to your code
```php
...
    here()->dump();
...
```
# Avilable API
- `here()`, register dump log.
- `here()->dump()`, dump current line.
- `here()->dumpAll()`, dump all registered.
- `here()->count()`, count how many execution by group name.
- `here()->countAll()`, count all group.

### Other
- `here()->dumpIf()`, dump current line (if condition true).
- `here()->info()`, dump current line (without code snapshot).
- `track()`, print debug backtrace information.
- `work()`, short hand for `here()->dump()`.

# Todo
- [X] Socket reporting.
- [ ] Socket reporting - browser view.
- [X] `here()->count()` group by filename.

