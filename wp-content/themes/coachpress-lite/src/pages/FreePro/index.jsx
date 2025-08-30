
import freevspro from "../../assets/img/freevspro.jpg";
import { Sidebar, Icon } from "../../components";
import { __ } from '@wordpress/i18n';
const FreePro = () => {

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
                    <img className="freepro" src={freevspro} alt={__("Free vs Pro image", "coachpress-lite")} />
                </div>
                <Sidebar sidebarSettings={sidebarSettings} openInNewTab={true}/>
            </div>
        </>
    )
}

export default FreePro;