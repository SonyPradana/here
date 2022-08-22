# Dump 'here'
Dump your current line of code, for make sure your on right tract.

# Installation
```bash
composer require sonypradana/here
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

# Todo
- [ ] Socket reporting.
- [ ] Socket reporting - browser view.
- [X] `here()->count()` group by filename.

