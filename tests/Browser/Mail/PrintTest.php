<?php

namespace Roundcube\Tests\Browser\Mail;

use Roundcube\Tests\Browser\Bootstrap;
use Roundcube\Tests\Browser\Components\App;
use Roundcube\Tests\Browser\Components\Popupmenu;
use Roundcube\Tests\Browser\TestCase;

class PrintTest extends TestCase
{
    #[\Override]
    public static function setUpBeforeClass(): void
    {
        Bootstrap::init_imap(true);
        Bootstrap::purge_mailbox('INBOX');

        // import email messages
        foreach (glob(TESTS_DIR . 'data/mail/list_00.eml') as $f) {
            Bootstrap::import_message($f, 'INBOX');
        }
    }

    /**
     * Test Print action
     */
    public function testPrint()
    {
        $this->browse(function ($browser) {
            $browser->go('mail');

            $browser->waitFor('#messagelist tbody tr:first-child')
                ->ctrlClick('#messagelist tbody tr:first-child');

            $browser->clickToolbarMenuItem('more', null, false);

            $browser->with(new Popupmenu('message-menu'), function ($browser) use (&$current_window, &$new_window) {
                if ($browser->isPhone()) {
                    $browser->assertMissing('a.print');
                    $this->markTestSkipped();
                }

                [$current_window, $new_window] = $browser->openWindow(static function ($browser) {
                    $browser->clickMenuItem('print');
                });
            });

            $browser->driver->switchTo()->window($new_window);

            $browser->with(new App(), static function ($browser) {
                $browser->assertEnv([
                    'task' => 'mail',
                    'action' => 'print',
                ]);
            });

            $browser->assertVisible('img.contactphoto')
                ->assertSeeIn('.subject', 'Lines')
                ->assertSeeIn('.message-part div.pre', 'Plain text message body.')
                ->assertVisible('.message-part div.pre .sig')
                // Tests "more recipients" link
                ->with('.header-headers .header.cc', static function ($browser) {
                    $browser->assertSee('test10@domain.tld')
                        ->assertDontSee('test11@domain.tld')
                        ->assertSeeIn('a.morelink', '2 more...')
                        ->assertElementsCount('span.adr', 10)
                        ->click('a.morelink')
                        ->assertElementsCount('span.adr', 12)
                        ->assertSee('test12@domain.tld');
                });

            $browser->driver->close();
            $browser->driver->switchTo()->window($current_window);
        });
    }
}
