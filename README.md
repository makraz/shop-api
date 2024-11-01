# How to Install DDEV and Docker and Set Up Our Project

To set up our project, you first need to install **DDEV** and **Docker**. Here are the steps to install them:

## Installing Docker

1. Click on this [link](https://www.docker.com/) and choose which system you have (Windows, Mac, Linux).
>If you are using Windows Home, make sure that you enable virtualization on Bios. If you don't know how to enable it, visit this [link](https://www.youtube.com/watch?v=MOuTxfzCvMY&ab_channel=MKBMobileDetailing) in YouTube.

## Installing DDEV

### For Linux:

#### Install a locally-trusted certificate with mkcert

Modern browsers require valid certificates, which mkcert can create. Install mkcert and run this:

```bash
# Initialize mkcert
mkcert -install
```

#### Debian/Ubuntu:

DDEV’s Debian and RPM packages work with apt and yum repositories and most variants that use them, including Windows WSL2.

```bash
# Add DDEV’s GPG key to your keyring
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://pkg.ddev.com/apt/gpg.key | sudo apt-key --keyring /etc/apt/keyrings/ddev.gpg add -

# Add DDEV releases to your package repository
echo "deb [signed-by=/etc/apt/keyrings/ddev.gpg] https://pkg.ddev.com/apt/ * *" \
    | sudo tee /etc/apt/sources.list.d/ddev.list >/dev/null

# Update package information and install DDEV
sudo apt update && sudo apt install -y ddev
```

##### Need to remove a previously-installed variant?

#### Fedora, Red Hat, etc.

```bash
# Add DDEV releases to your package repository
echo '[ddev]
name=ddev
baseurl=https://pkg.ddev.com/yum/
gpgcheck=0
enabled=1' | sudo tee /etc/yum.repos.d/ddev.repo >/dev/null

# Install DDEV
sudo dnf install --refresh ddev
```

##### Signed yum repository support will be added in the future.

#### Arch Linux

We maintain the ddev-bin package in AUR for Arch-based systems including Arch Linux, EndeavourOS, and Manjaro. Install with yay or your AUR tool of choice.

```bash
# Install DDEV
yay -S ddev-bin
```

#### Homebrew (AMD64 only)

```bash
# Install DDEV using Homebrew
brew install ddev/ddev/ddev
```

##### Install Script

The install script downloads, verifies, and sets up the ddev binary.

```bash
# Download and run the install script
curl -fsSL https://ddev.com/install.sh | bash
```

##### Need a specific version?

Use the `-s` argument to specify a specific stable or pre-release version.

```bash
# Download and run the script to install DDEV v1.21.4
curl -fsSL https://ddev.com/install.sh | bash -s v1.21.4
```

### For Mac:

#### Homebrew

Homebrew is the easiest and most reliable way to install and upgrade DDEV:

```bash
# Install DDEV
brew install ddev/ddev/ddev

# Initialize mkcert
mkcert -install
```

The install script downloads, verifies, and sets up the ddev binary:

```bash
# Download and run the install script
curl -fsSL https://ddev.com/install.sh | bash
```

##### Need a specific version?

Use the `-s` argument to specify a specific stable or pre-release version.

```bash
# Download and run the script to install DDEV v1.21.4
curl -fsSL https://ddev.com/install.sh | bash -s v1.21.4
```

### For Windows:

There are three ways to install DDEV, but the best way is by following these steps:

#### WSL2/Docker Desktop Manual Installation

You can manually step through the process the install script attempts to automate:

1. Install Chocolatey:

```PowerShell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://chocolatey.org/install.ps1'))
```

2. In an administrative PowerShell, run `choco install -y ddev mkcert`.

3. In an administrative PowerShell, run `mkcert -install` and follow the prompts to install the Certificate Authority.

4. In an administrative PowerShell, run `$env:CAROOT="$(mkcert -CAROOT)"; setx CAROOT $env:CAROOT; If ($Env:WSLENV -notlike "*CAROOT/up:*") { $env:WSLENV="CAROOT/up:$env:WSLENV"; setx WSLENV $Env:WSLENV }`. This will set WSL2 to use the Certificate Authority installed on the Windows side. In some cases, it takes a reboot to work correctly.

5. In an administrative PowerShell, run `wsl --install`. This will install WSL2 and Ubuntu for you. Reboot when this is done.

6. **Docker Desktop for Windows**: If you already have the latest Docker Desktop, configure it in the General Settings to use the WSL2-based engine. Otherwise, install the latest Docker Desktop for Windows and select the WSL2-based engine (not legacy Hyper-V) when installing. Install with Chocolatey by running `choco install docker-desktop`, or download the installer from desktop.docker.com. Start Docker. It may prompt you to log out and log in again, or reboot.

7. Go to Docker Desktop’s Settings → Resources → WSL integration → enable integration for your distro. Now, Docker commands will be available from within your WSL2 distro.

8. Double-check in PowerShell: `wsl -l -v` should show three distros, and your Ubuntu should be the default. All three should be WSL version 2.

9. Double-check in Ubuntu (or your distro): `echo $CAROOT` should show something like `/mnt/c/Users/<you>/AppData/Local/mkcert`.

10. Check that Docker is working inside Ubuntu (or your distro) by running `docker ps`.

11. Open the WSL2 terminal, for example, Ubuntu from the Windows start menu.

12. Install DDEV:

    ```bash
    curl -fsSL https://pkg.ddev.com/apt/gpg.key | gpg --dearmor | sudo tee /etc/apt/keyrings/ddev.gpg > /dev/null
    echo "deb [signed-by=/etc/apt/keyrings/ddev.gpg] https://pkg.ddev.com/apt/ * *" | sudo tee /etc/apt/sources.list.d/ddev.list >/dev/null
    sudo apt update && sudo apt install -y ddev
    ```

13. In WSL2, run `mkcert -install`.
>for more info click [here](https://ddev.readthedocs.io/en/latest/users/install/).

>You have now installed DDEV on WSL2. If you’re using WSL2 for DDEV, remember to run all ddev commands inside the WSL2 distro.

## Set Up Github Desktop

After you have installed DDEV and Docker, go to Setup Github Desktop and log in using your account.

Next, open **Git** in your folder using this path: `C:\Users\<You>\OneDrive\Documents\GitHub`.

Add this command to **clone our project**

For HTTPS:

```bash
git clone https://github.com/makraz/shop-api.git
```

For SSH:

```bash
git clone git@github.com:makraz/shop-api.git
```

Enter the **shop-api** folder and open Git or command prompt and then follow these commands, it may take a few minutes:

```bash
ddev start
ddev composer install
```

```bash
ddev start
ddev composer install
```
