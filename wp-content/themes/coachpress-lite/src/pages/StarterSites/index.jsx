import { Icon, Card } from "../../components";
import { __ } from "@wordpress/i18n";
import { mainDemo, demo2, demo3, demo4, demo5, demo6 } from "../../components/images";

const StarterSites = () => {
    const cardList = [
        {
            heading: __('CoachPress', 'coachpress-lite'),
            imageurl: mainDemo,
            buttonUrl: __('https://blossomthemesdemo.com/coachpress/', 'coachpress-lite'),
        },
        {
            heading: __('Life Coach (Elementor)', 'coachpress-lite'),
            imageurl: demo2,
            buttonUrl: __('https://blossomthemesdemo.com/coachpress-life-coaching/', 'coachpress-lite'),
        },
        {
            heading: __('Business Coach (Elementor)', 'coachpress-lite'),
            imageurl: demo3,
            buttonUrl: __('https://blossomthemesdemo.com/coachpress-business-coach/', 'coachpress-lite'),
        },
        {
            heading: __('Health Nutritionist (Elementor)', 'coachpress-lite'),
            imageurl: demo4,
            buttonUrl: __('https://blossomthemesdemo.com/coachpress-2/', 'coachpress-lite'),
        },
        {
            heading: __('Ikigai Coach (Elementor)', 'coachpress-lite'),
            imageurl: demo5,
            buttonUrl: __('https://blossomthemesdemo.com/coachpress-3/', 'coachpress-lite'),
        },
        {
            heading: __('Confidence Coach (Elementor)', 'coachpress-lite'),
            imageurl: demo6,
            buttonUrl: __('https://blossomthemesdemo.com/coachpress-4/', 'coachpress-lite'),
        },

    ]
    return (
        <>
            <Card
                cardList={cardList}
                cardPlace='starter'
                cardCol='three-col'
            />
            <div className="starter-sites-button cw-button">
                <a href={__( 'https://blossomthemes.com/theme-demo/?theme=coachpress&utm_source=coachpress-lite&utm_medium=dashboard&utm_campaign=theme_demo', 'coachpress-lite' )} target="_blank" className="cw-button-btn outline">
                    {__('View All Demos', 'coachpress-lite')}
                    <Icon icon="arrowtwo" />
                </a>
            </div>
        </>
    );
}

export default StarterSites;