<?php
namespace Lacasera;

class ShowDownloader
{
    protected $showList = "https://syntax.fm/api/shows";

    protected $showDirectory = "./shows";

    public function go()
    {
        $this->createShowsDirectory();

        $shows = $this->getShowList();

        foreach ($shows as $episode) {
            $this->downloadEpisode($episode);
        }

        echo "Download Completed.. 😃". PHP_EOL;
    }

    private function getShowList(): array
    {
        return json_decode(file_get_contents($this->showList));
    }

    private function getEpisodeName($show): string
    {
        $stripedTitle = str_replace('/', '', $show->title);
        return "Syntax {$show->number} - {$stripedTitle} - {$show->displayDate}.mp3";
    }

    private function downloadEpisode($episode): void
    {
        $episodeName  = $this->getEpisodeName($episode);

        echo "Downloading $episodeName". PHP_EOL;

        $path = $this->buildEpisodePath( $episodeName );
        if (!$this->episodeExists($path)) {
            copy($episode->url, $path);
        }
    }

    private function createShowsDirectory(): bool
    {
       return !is_dir($this->showDirectory) ? mkdir($this->showDirectory) : false;
    }

    private function episodeExists($episodeName): bool
    {
        $episodeFile = $this->buildEpisodePath($episodeName);

        return file_exists($episodeFile) && is_file($episodeFile);
    }

    private function buildEpisodePath($episodeName): string
    {
        return $this->showDirectory. "/". $episodeName;
    }
}