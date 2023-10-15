class Paste < Formula
    desc ""
    homepage ""
    url "https://github.com/ahmetbarut/paste-app-cli/blob/master/build/paste.phar"
    version "0.2"
    sha256 "188e7fcb1d7c46564642d4ca776c739871b76836b4d76a8c9e5478ae70cce47f"
    license ""

    def install
        bin.install "paste.phar" => "paste-app"
        system "./configure", *std_configure_args, "--disable-silent-rules"
    end
  end
  