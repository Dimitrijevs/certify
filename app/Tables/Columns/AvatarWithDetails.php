<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class AvatarWithDetails extends Column
{
    protected string $view = 'tables.columns.avatar-with-details';

    protected string|\Closure|null $title = null;

    protected string|\Closure|null $description = null;

    protected string|\Closure|null $avatar = null;

    // image, name_xs, icon
    protected string|\Closure|null $avatar_type = null;

    protected string|\Closure|null $bg_color = null;

    // xs - h-6, w-6, sm - h-7, w-7, md - h-8, w-8, lg - h-9, w-9, xl - h-10, w-10, 2xl - h-11, w-11
    protected string $bg_size = 'h-9 w-9'; // lg

    // only for employee teams
    protected string|\Closure|null $name_xs = null;

    protected string|\Closure|null $icon = null;

    protected string|\Closure|null $avatar_color = null;

    protected string|\Closure|null $descriptionIcon = null;

    // pass as a string, example: clients/1/edit
    protected string|\Closure|null $link = null;

    protected bool|\Closure $isTracking = false;

    protected bool|\Closure $isOnline = false;

    // default is 20
    protected int|\Closure $title_limit = 20;

    // default is 26
    protected int|\Closure $description_limit = 26;

    // xs = text-xs or h-4 w-4, sm = text-sm or h-5 w-5, md = text-md or h-6 w-6, lg = text-lg or h-7 w-7, xl = text-xl or h-8 w-8, 2xl = text-2xl or h-9 w-9
    protected string|\Closure|null $avatar_size = null;

    public function setIsTracking(bool|\Closure $value): static
    {
        $this->isTracking = $value;
        return $this;
    }

    public function setIsOnline(bool|\Closure $value): static
    {
        $this->isTracking = true;
        $this->isOnline = $value;
        return $this;
    }

    public function getIsTracking(): bool
    {
        return $this->evaluate($this->isTracking);
    }

    public function getIsOnline(): bool
    {
        return $this->evaluate($this->isOnline);
    }


    public function description(string|\Closure $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function descriptionIcon(string|\Closure $descriptionIcon): static
    {
        $this->descriptionIcon = $descriptionIcon;
        return $this;
    }

    public function title(string|\Closure $title): static
    {
        $this->title = $title;
        return $this;
    }

    // image, name_xs, icon
    public function avatarType(string|\Closure $avatarType): static
    {
        $this->avatar_type = $avatarType;
        return $this;
    }

    public function avatar(string|\Closure $avatar = null, string|\Closure $name = null): static
    {
        if ($name instanceof \Closure) {
            $name = $this->evaluate($name);
        }

        if (is_null($avatar)) {
            $words = explode(' ', $name);
            if (count($words) > 1) {
                $firstLetter = strtoupper(substr($words[0], 0, 1));
                $secondLetter = strtoupper(substr($words[1], 0, 1));
                $initials = $firstLetter . $secondLetter;
                $this->avatar = $initials;
            } else {
                $initials = strtoupper(substr($name, 0, 2));
                $this->avatar = $initials;
            }
        } else {
            $this->avatar = $avatar;
        }

        return $this;
    }


    public function bgColor(string|\Closure $bgColor = null): static
    {
        $this->bg_color = $bgColor;
        return $this;
    }

    // xs - h-6, w-6, sm - h-7, w-7, md - h-8, w-8, lg - h-9, w-9, xl - h-10, w-10, 2xl - h-11, w-11
    public function bgSize(string $bgSize = null): static
    {
        if ($bgSize == 'xs') {
            $this->bg_size = 'h-6 w-6';
        } else if ($bgSize == 'sm') {
            $this->bg_size = 'h-7 w-7';
        } else if ($bgSize == 'md') {
            $this->bg_size = 'h-8 w-8';
        } else if ($bgSize == 'lg') {
            $this->bg_size = 'h-9 w-9';
        } else if ($bgSize == 'xl') {
            $this->bg_size = 'h-10 w-10';
        } else if ($bgSize == '2xl') {
            $this->bg_size = 'h-11 w-11';
        }

        return $this;
    }

    public function nameXs(string|\Closure $nameXs = null): static
    {
        $this->name_xs = $nameXs;
        return $this;
    }

    public function icon(string|\Closure $icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function iconColor(string|\Closure $avatarColor)
    {
        $this->avatar_color = $avatarColor;
        return $this;
    }

    // pass as a string, example clients/1/edit
    public function link(string|\Closure $link)
    {
        $this->link = $link;
        return $this;
    }

    public function titleLimit(string|\Closure $titleLimit): static
    {
        $this->title_limit = $titleLimit;
        return $this;
    }

    public function descriptionLimit(string|\Closure $descriptionLimit): static
    {
        $this->description_limit = $descriptionLimit;
        return $this;
    }

    // xs = text-xs or h-4 w-4, sm = text-sm or h-5 w-5, md = text-md or h-6 w-6, lg = text-lg or h-7 w-7, xl = text-xl or h-8 w-8, 2xl = text-2xl or h-9 w-9
    public function avatarSize(string|\Closure $avatarSize): static
    {
        if ($this->avatar_type == 'name_xs') {
            if ($avatarSize == 'xs') {
                $this->avatar_size = 'text-xs';
            } else if ($avatarSize == 'sm') {
                $this->avatar_size = 'text-sm';
            } else if ($avatarSize == 'md') {
                $this->avatar_size = 'text-md';
            } else if ($avatarSize == 'lg') {
                $this->avatar_size = 'text-lg';
            } else if ($avatarSize == 'xl') {
                $this->avatar_size = 'text-xl';
            } else if ($avatarSize == '2xl') {
                $this->avatar_size = 'text-2xl';
            }
        } else if ($this->avatar_type == 'icon' || $this->avatar_type == 'image') {
            if ($avatarSize == 'xs') {
                $this->avatar_size = 'h-4 w-4';
            } else if ($avatarSize == 'sm') {
                $this->avatar_size = 'h-5 w-5';
            } else if ($avatarSize == 'md') {
                $this->avatar_size = 'h-6 w-6';
            } else if ($avatarSize == 'lg') {
                $this->avatar_size = 'h-7 w-7';
            } else if ($avatarSize == 'xl') {
                $this->avatar_size = 'h-8 w-8';
            } else if ($avatarSize == '2xl') {
                $this->avatar_size = 'h-9 w-9';
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->evaluate($this->title);
    }

    public function getDescription(): ?string
    {
        return $this->evaluate($this->description);
    }

    public function getAvatar(): ?string
    {
        return $this->evaluate($this->avatar);
    }

    public function getBgColor(): ?string
    {
        return $this->evaluate($this->bg_color);
    }

    public function getBgSize(): ?string
    {
        return $this->evaluate($this->bg_size);
    }

    public function getAvatarType(): ?string
    {
        return $this->evaluate($this->avatar_type);
    }

    public function getNameXs(): ?string
    {
        return $this->evaluate($this->name_xs);
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function getAvatarColor(): ?string
    {
        return $this->evaluate($this->avatar_color);
    }

    public function getDescriptionIcon()
    {
        return $this->evaluate($this->descriptionIcon);
    }

    public function getLink(): ?string
    {
        return $this->evaluate($this->link);
    }

    public function getTitleLimit()
    {
        return $this->evaluate($this->title_limit);
    }

    public function getDescriptionLimit()
    {
        return $this->evaluate($this->description_limit);
    }

    public function getAvatarSize()
    {
        if ($this->avatar_size == null) {
            if ($this->avatar_type == 'name_xs') {
                if ($this->avatar_size == 'xs') {
                    return 'text-xs';
                } else if ($this->avatar_size == 'sm') {
                    return 'text-sm';
                } else if ($this->avatar_size == 'md') {
                    return 'text-md';
                } else if ($this->avatar_size == 'lg') {
                    return 'text-lg';
                } else if ($this->avatar_size == 'xl') {
                    return 'text-xl';
                } else if ($this->avatar_size == '2xl') {
                    return 'text-2xl';
                }
            } else if ($this->avatar_type == 'icon' || $this->avatar_type == 'image') {
                if ($this->avatar_size == 'xs') {
                    return 'h-4 w-4';
                } else if ($this->avatar_size == 'sm') {
                    return 'h-5 w-5';
                } else if ($this->avatar_size == 'md') {
                    return 'h-6 w-6';
                } else if ($this->avatar_size == 'lg') {
                    return 'h-7 w-7';
                } else if ($this->avatar_size == 'xl') {
                    return 'h-8 w-8';
                } else if ($this->avatar_size == '2xl') {
                    return 'h-9 w-9';
                }
            }
        }

        return $this->evaluate($this->avatar_size);
    }
}
