var zvirata = {
{foreach from=$zvirata item=zvire name=z}
{$zvire.id|replace:'-':'_'}:{literal}{{/literal}c:'{$zvire.jmeno}'{literal}}{/literal}{if !$smarty.foreach.z.last},{/if}

{/foreach}
}
