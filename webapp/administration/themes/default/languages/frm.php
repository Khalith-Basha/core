<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
?>

                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php
echo osc_current_admin_theme_url('images/icon-language.png'); ?>" title="" alt="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php
_e('Edit'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <!-- add edit language form -->
                <div id="settings_form">
                    <form action="<?php
echo osc_admin_base_url(true); ?>" method="post">
                        <input type="hidden" name="page" value="languages" />
                        <input type="hidden" name="action" value="edit_post" />
                        <?php
LanguageForm::primary_input_hidden($aLocale); ?>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Name'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::name_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Short name'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::short_name_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Description'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::description_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Currency format'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::currency_format_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Number of decimals'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::num_dec_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Decimal point'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::dec_point_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Thousands separator'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::thousands_sep_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Date format'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::date_format_input_text($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php
_e('Stopwords'); ?></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::description_textarea($aLocale); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::enabled_input_checkbox($aLocale); ?>&nbsp;<label for="b_enabled"><?php
_e('Enabled for the public website'); ?></label>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"></div>
                            <div class="FormElementInput">
                            <?php
LanguageForm::enabled_bo_input_checkbox($aLocale); ?>&nbsp;<label for="b_enabled_bo"><?php
_e('Enabled for the backoffice (administration)'); ?></label>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"></div>
                            <div class="FormElementInput">
                                <button class="formButton" type="button" onclick="window.location='<?php
echo osc_admin_base_url(true); ?>?page=languages';" ><?php
_e('Cancel'); ?></button>
                                <button class="formButton" type="submit"><?php
_e('Update'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>

