<h1><a href="index.html" class="hlavicka">{$title}</a></h1>

{foreach from=$zvire.clanek item=info}

{if $info.typ == 'text'}
{if $info.text == 'Základní údaje'}
<h3>{$info.text}</h3>
{else}
<p>
{$info.text}
</p>
{/if}
{/if}

{if $info.typ == 'img'}
<div class="obrazek"><a href="{$info.img.id}.jpeg"><img src="{$info.img.id}.jpeg" style="width:100%;max-width:45rem;" class="obr"></a>{if $info.img.popis_ascii != $title_ascii}<p>{$info.img.popis}</p>{/if}</div>
{/if}

{if $info.typ == 'mp3'}
<p>
<label for="{$info.mp3.id}"><h3>{$info.mp3.popis}</h3></label>
<audio
    id="{$info.mp3.id}"
    controls
    src="{$info.mp3.id}.mp3">
</audio>
</p>
{/if}

{/foreach}

{foreach from=$zvire.nahravky item=info}
{if $info == 'Jak vznikaly nahrávky'}
<h3>{$info}</h3>
{else}
<p>{$info}</p>
{/if}
{/foreach}

<ul class="nav">
{if isset($prev)}
<li><a href="{$prev.file}">&laquo; {$prev.zvire.jmeno}</a></li>
{/if}
{if isset($next)}
<li><a href="{$next.file}">{$next.zvire.jmeno} &raquo;</a></li>
{/if}
</ul>
