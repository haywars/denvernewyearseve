<?

elapsed('begin basic-event');

unset($img);

$venue = $event->where;
$address = array($venue['city'], $venue['state'], $venue['zip']);

$tickets = $event->tickets;
$show_this_many_tickets = 4;

$ct_event = $event->getEvent();


?>
    <div class="m vevent">
    	<? krumo($event) ?>
        <div class="title-bar">
            <div class="title-info">
                <div class="title summary">
                    <a href="<?=$event->uri?>" class="url"><?=$event->seo['name']?></a>
                </div>
                <div class="subtitle">
                    <?=$titles['modifier']?$titles['modifier'].'&emsp;':''?>
                    <span>
<?
                        if ($event->where['address1']) {
                            echo $event->where['address1'] . ', ';
                        }
?>
                        <span class="location">
                            <?=implode(' ', $address)?>
                        </span>
                    </span>
                </div>
            </div>
            <div class="social">
                <div class="facebook">
                    <div class="ticket_disclaimer">
                        * Prices go up based on availability
                    </div>
<? /*
                	<div class="fb-like" data-href="<?=$website->domain?><?=$event['url']?>" data-send="false" data-layout="button_count" data-width="80" data-show-faces="true" <?=$website->theme=='dark'?'data-colorscheme="dark"':''?>></div>
*/ ?>
                </div>
                <div class="twitter"></div>
                <div class="google"></div>
            </div>
        </div>
        <div class="content">
            <div class="tickets">
                <div class="when">
                    <div class="date dtstart">
                        <?=date('l F jS, Y', $event->when['date']['U'])?>
                    </div>
                    <div class="time">
                        <?=$event->when['door_time']['formatted']?>
<?
                        if ($event->when['close_time']) {
                            echo ' - ' . $event->when['close_time']['formatted'];
                        }
?>
                    </div>
                </div>
<?
            $show_this_many_tickets = 5;

			$count = 0;
            foreach ( $tickets as $ticket ) {

                $ct_ticket_id = $ticket['ide'];

                if ( $count >= $show_this_many_tickets ) break;

				if( $ticket ) {
                    $count++;
?>
                <div class="ticket-row">
                    <div class="ticket-name"><?=$ticket['name']?></div>
                    <div class="ticket-price"><?=$ticket['message']?></div>
                </div>
<?
				}


			}

            if ( count($tickets) > $show_this_many_tickets) {
                $more_tix = count($tickets) - $show_this_many_tickets;
?>
                <div class="more_options">
                    <a href="<?=$event->uri?>"><?=$more_tix?>
                        More Ticket Options &raquo;</a>
                </div>
<?
			}
?>
            </div>
            <div class="image">
            	<a href="<?=$event->uri?>">
<?
                $conf = array(
                    'width' => 240,
					'height' => 200,
					'crop' => 'center'
				);

				$media_ids = $ct_event->getMediaIDs(4);
				if ($ct_event->featured__media_item_id || $event->is['bc']) {
                    unset($conf['crop']);
				}

				foreach ($media_ids as $mid) {
					$img = vf::getItem($mid, $conf);
					if (!$img->errors)
						break;

				}

                // temporary for debugging
                if (false && !$img->src) {
                    echo $mid;
                    krumo($media_ids);
                    krumo($img);
                    exit;
                }

                $h = (200 - $img->height) / 2;
                $w = (240 - $img->width) / 2;

                $style = vsprintf('margin: %spx %spx %spx %spx;', array(
                    ceil($h),
                    ceil($w),
                    floor($h),
                    floor($w)
                ));

?>
                    <img src="<?=$img->src?>" style="<?=$style?>" />
            	</a>
            </div>
            <div class="info">
            	<div class="info-top">
                    <div class="headline"><?=($event->headline)?:$event->event_name?></div>
                        <div class="openbar"><?=$event->promotion?></div>
                </div>
                <div class="info-bottom">
                	<div class="buttons">
<?
                    if (
                        in_array($event->status, array('A','B','H'))
                        && count($event->tickets) > 0
                        ) {
?>
                    	<div class="info-button">
                            <a href="<?=$event->uri?>">More Info</a>
                        </div>
                        <div class="buy-button">
<?
                        if ($event->status == 'B') {
?>
                            On Sale Soon!
<?
                        } else {
?>
                            <a href="<?=$event->buy_url?>">Buy Tickets</a>
<?
                        }
?>
                        </div>
<?
                    } else {
?>
                    	<div class="single-button">
                            <a href="<?=$event->uri?>">More Info</a>
                        </div>
<?
                    }
?>
                    </div>
<?
                    if (count($event->tags) > 0) {
?>
                    <div class="tags">Tags:
<?
                    	foreach ($event->tags as $key => $tag) {
							if ($key != 0) {
                                echo ", ";
                            }
							echo $tag;
						}
?>
                    </div>
<?
                    }
?>
                </div>
			</div>
        </div>
    </div>
