# Dump 'here'
Dump your current line of code, for make sure your on right tract.

# Installation
```bash
composer require sonypradana/here --dev
```
# How to Use
Put `here()` anywhere in your code
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
- `here()->dumpIf()`, dump the current line if a condition is true.
- `here()->info()`, dump the current line without code snapshot.
- `track()`, print debug backtrace information.
- `work()`, a short hand for `here()->dump()`.

# Todo
- [X] Implement socket reporting.
- [ ] Implement socket reporting with a browser view.
- [X] `here()->count()` Implement here()->count() with grouping by filename.

