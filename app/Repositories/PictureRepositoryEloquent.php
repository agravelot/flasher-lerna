<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Picture;
use App\Repositories\Contracts\PictureRepository;
use App\Validators\PictureValidator;
use Illuminate\Http\UploadedFile;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PictureRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PictureRepositoryEloquent extends BaseRepository implements PictureRepository
{

    public function update(array $attributes, $id)
    {
        return parent::update($attributes, $id);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Picture::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function createForAlbum(array $attributes, Album $album)
    {
        $pictures = [];
        $path = 'albums/' . $album->id . '/';

        foreach ($attributes as $attribute) {
            $attribute = UploadedFile::createFromBase($attribute);
            $picture = new Picture();
            $picture->filePath = $attribute->store($path, ['disk' => 'uploads']);
            $picture->originalName = $attribute->getClientOriginalName();
            $picture->mineType = $attribute->getMimeType();
            $picture->hashName = $attribute->getFilename();
            $picture->extension = $attribute->guessExtension();
            $picture->size = $attribute->getSize();
            $pictures[] = $picture;
        }

        $album->pictures()->saveMany($pictures);
        $album->pictureHeader()->save($pictures[0]);
        $album->save();

        return $album;
    }
}
