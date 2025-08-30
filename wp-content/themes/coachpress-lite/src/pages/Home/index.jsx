import { Icon, Sidebar, Card, Heading } from "../../components";
import { __ } from '@wordpress/i18n';

const Homepage = () => {
    const cardLists = [
        {
            iconSvg: <Icon icon="site" />,
            heading: __('Site Identity', 'coachpress-lite'),
            buttonText: __('Customize', 'coachpress-lite'),
            buttonUrl: cw_dashboard.custom_logo
        },
        {
            iconSvg: <Icon icon="colorsetting" />,
            heading: __("Color Settings", 'coachpress-lite'),
            buttonText: __('Customize', 'coachpress-lite'),
            buttonUrl: cw_dashboard.colors
        },
        {
            iconSvg: <Icon icon="layoutsetting" />,
            heading: __("Layout Settings", 'coachpress-lite'),
            buttonText: __('Customize', 'coachpress-lite'),
            buttonUrl: cw_dashboard.layout
        },
        {
            iconSvg: <Icon icon="instagramsetting" />,
            heading: __("Instagram Settings", 'coachpress-lite'),
            buttonText: __('Customize', 'coachpress-lite'),
            buttonUrl: cw_dashboard.instagram
        },
        {
            iconSvg: <Icon icon="generalsetting" />,
            heading: __("General Settings"),
            buttonText: __('Customize', 'coachpress-lite'),
            buttonUrl: cw_dashboard.general
        },
        {
            iconSvg: <Icon icon="footersetting" />,
            heading: __('Footer Settings', 'coachpress-lite'),
            buttonText: __('Customize', 'coachpress-lite'),
            buttonUrl: cw_dashboard.footer
        }
    ];

    const proSettings = [
        {
            heading: __('Header Layouts', 'coachpress-lite'),
            para: __('Choose from different unique header layouts.', 'coachpress-lite'),
            buttonText: __('Learn More', 'coachpress-lite'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Multiple Layouts', 'coachpress-lite'),
            para: __('Choose layouts for blogs, banners, posts and more.', 'coachpress-lite'),
            buttonText: __('Learn More', 'coachpress-lite'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Multiple Sidebar', 'coachpress-lite'),
            para: __('Set different sidebars for posts and pages.', 'coachpress-lite'),
            buttonText: "Learn More",
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('WP Custom Fonts', 'coachpress-lite'),
            para: __('Easily upload your own local font using this plugin.', 'coachpress-lite'),
            buttonText: __('Learn More', 'coachpress-lite'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Boost your website performance with ease.', 'coachpress-lite'),
            heading: __('Performance Settings', 'coachpress-lite'),
            buttonText: __('Learn More', 'coachpress-lite'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Choose typography for different heading tags.', 'coachpress-lite'),
            heading: __('Typography Settings', 'coachpress-lite'),
            buttonText: __('Learn More', 'coachpress-lite'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Import the demo content to kickstart your site.', 'coachpress-lite'),
            heading: __('One Click Demo Import', 'coachpress-lite'),
            buttonText: __('Learn More', 'coachpress-lite'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Easily add notification bar with CTA/Newsletter for conversion', 'coachpress-lite'),
            heading: __('Notification Bar Settings', 'coachpress-lite'),
            buttonText: __('Learn More', 'coachpress-lite'),
            buttonUrl: cw_dashboard?.get_pro
        },
    ];

    const sidebarSettings = [
        {
            heading: __('We Value Your Feedback!', 'coachpress-lite'),
            icon: "star",
            para: __("Your review helps us improve and assists others in making informed choices. Share your thoughts today!", 'coachpress-lite'),
            imageurl: <Icon icon="review" />,
            buttonText: __('Leave a Review', 'coachpress-lite'),
            buttonUrl: cw_dashboard.review
        },
        {
            heading: __('Knowledge Base', 'coachpress-lite'),
            para: __("Need help using our theme? Visit our well-organized Knowledge Base!", 'coachpress-lite'),
            imageurl: <Icon icon="documentation" />,
            buttonText: __('Explore', 'coachpress-lite'),
            buttonUrl: cw_dashboard.docmentation
        },
        {
            heading: __('Need Assistance? ', 'coachpress-lite'),
            para: __("If you need help or have any questions, don't hesitate to contact our support team. We're here to assist you!", 'coachpress-lite'),
            imageurl: <Icon icon="supportTwo" />,
            buttonText: __('Submit a Ticket', 'coachpress-lite'),
            buttonUrl: cw_dashboard.support
        }
    ];

    return (
        <>
            <div className="customizer-settings">
                <div className="cw-customizer">
                    <div className="video-section">
                        <div className="cw-settings">
                            <h2>{__('CoachPress Lite Tutorial', 'coachpress-lite')}</h2>
                        </div>
                        <iframe src="https://www.youtube.com/embed/-_hg-aj2PBc" title={__( 'Start a Lead Generating Coaching Website in 2023 | CoachPress Lite', 'coachpress-lite' )}   frameBorder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerPolicy="strict-origin-when-cross-origin" allowFullScreen></iframe>
                    </div>
                    <Heading
                        heading={__( 'Quick Customizer Settings', 'coachpress-lite' )}
                        buttonText={__( 'Go To Customizer', 'coachpress-lite' )}
                        buttonUrl={cw_dashboard?.customizer_url}
                        openInNewTab={true}
                    />
                    <Card
                        cardList={cardLists}
                        cardPlace='customizer'
                        cardCol='three-col'
                    />
                    <Heading
                        heading={__( 'More features with Pro version', 'coachpress-lite' )}
                        buttonText={__( 'Go To Customizer', 'coachpress-lite' )}
                        buttonUrl={cw_dashboard?.customizer_url}
                        openInNewTab={true}
                    />
                    <Card
                        cardList={proSettings}
                        cardPlace='cw-pro'
                        cardCol='two-col'
                    />
                    <div className="cw-button">
                        <a href={cw_dashboard?.get_pro} target="_blank" className="cw-button-btn primary-btn long-button">{__('Learn more about the Pro version', 'coachpress-lite')}</a>
                    </div>
                </div>
                <Sidebar sidebarSettings={sidebarSettings} openInNewTab={true} />
            </div>
        </>
    );
}

export default Homepage;