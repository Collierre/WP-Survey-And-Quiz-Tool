# Add an "alert" alias for long running commands.  Use like so:
#   sleep 10; alert
alias alert='notify-send --urgency=low -i "$([ $? = 0 ] && echo terminal || echo error)" "$(history|tail -n1|sed -e '\''s/^\s*[0-9]\+\s*//;s/[;&|]\s*alert$//'\'')"'

# To combine cd and ls

function cs()
{
  if [ $# -eq 0 ]; then
    cd && ls
  else
    cd "$*" && ls
  fi
}
alias cd=cs

export PS1="\[\033[0;35m\]\u@\h:\w \[\033[0m\]$ "

# For colour highlighting

export CLICOLOR=1
export LSCOLORS=ExFxCxDxBxegedabagacad

# Recommended by brew doctor
export PATH=/usr/local/sbin:$PATH

# So homebrew programs come before system ones
export PATH=/usr/local/bin:$PATH

# Setting PATH for Python 2.7
#PATH="/Library/Frameworks/Python.framework/Versions/2.7/bin:${PATH}"
#export PATH

# PHP 5.4 installed from liip
export PATH=/usr/local/php5/bin:$PATH

alias km="open -a 'Komodo Edit 8'"
alias py=python
alias la="ls -al"

alias bb=bbedit
alias fzd="cat ~/.filezilla/sitemanager.xml"
