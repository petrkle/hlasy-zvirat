var zvirata = {
{foreach from=$zvirata item=zvire name=z}
{$zvire.id|replace:'-':'_'}:{literal}{{/literal}c:'{$zvire.jmeno}',l:'{$zvire.lat}'{literal}}{/literal},
{/foreach}
{foreach from=$rubriky item=rubrika key=key name=z}
{$key|replace:'-':'_'}:{literal}{{/literal}c:'{$rubrika.jmeno}'{literal}}{/literal}{if !$smarty.foreach.z.last},{/if}

{/foreach}
}
