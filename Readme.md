# Paste App Cli

CLI ile terminal çıktılarını [paste.ahmetbarut.net](https://paste.ahmetbarut.net)'e yükleyin.

## Kurulum

```bash
composer global require ahmetbarut/paste-cli
```

## Kullanım

```bash
paste-cli create "İçerik"

# veya

paste-cli create "$(cat dosya.txt)"
```

## Not
Eğer komut çalışmıyorsa `~/.composer/vendor/bin` yolunu PATH değişkenine ekleyin.

Örnek:
```bash
export PATH="$PATH:$HOME/.composer/vendor/bin"
```

## Lisans

[MIT](https://choosealicense.com/licenses/mit/)