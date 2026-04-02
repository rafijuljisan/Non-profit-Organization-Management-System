<?php
namespace App\Policies;
use App\Models\Gallery;
class GalleryPolicy extends BasePolicy
{
    protected static string $permission = 'gallery';
}