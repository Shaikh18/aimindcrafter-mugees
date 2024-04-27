<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatPrompt;

class ChatPromptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ads = [
            ['id' => 1, 'group' => 'Social Media', 'title' => 'Instagram Captions', 'prompt' => 'Write 5 variations of Instagram captions for [product] that use humor to appeal to your target audience. Be creative and playful, but make sure to keep the message clear and concise. Use hashtags and emojis to add personality to the captions.', 'status' => true],
            ['id' => 2, 'group' => 'Social Media', 'title' => 'Facebook Post Ideas', 'prompt' => 'Create 5 Facebook post ideas for [brand] that involve user-generated content (UGC). Encourage followers to share their experiences with your product or service, and feature the best submissions. Use emoticons and hashtags to make the posts more engaging.', 'status' => true],
            ['id' => 3, 'group' => 'Social Media', 'title' => 'Twitter Thread', 'prompt' => 'Create a Twitter thread on [topic] that provides valuable information and actionable tips. Engage with followers and encourage them to share their thoughts. Use hashtags and emojis to make the thread more engaging.', 'status' => true],
            ['id' => 4, 'group' => 'Social Media', 'title' => 'TikTok Video Script', 'prompt' => 'Write a super engaging TikTok video script on [topic]. Each sentence should catch the viewer attention to make them keep watching.', 'status' => true],
            ['id' => 5, 'group' => 'Social Media', 'title' => 'Linkedin Post', 'prompt' => 'Create a narrative Linkedin post using immersive writing about [topic]. Details: [give details in bullet point format]. Use a mix of short and long sentences. Make it punchy and dramatic.', 'status' => true],
            ['id' => 6, 'group' => 'Social Media', 'title' => 'Instagram Story', 'prompt' => 'Write an Instagram story that will provide valuable and relevant information to my [ideal customer persona] about [subject] and persuade them to take [desired action] with a clear and compelling message.', 'status' => true],
            ['id' => 7, 'group' => 'Social Media', 'title' => 'Youtube Video Description', 'prompt' => 'Write a Youtube video description for [target audience] about [topic]. Mention [keywords] in the description and include a call to action at the end.', 'status' => true],
            ['id' => 8, 'group' => 'Social Media', 'title' => 'Hashtag Researcher', 'prompt' => 'What are some trendy and effective hashtags to use for [SPECIFIC SOCIAL MEDIA PLATFORM] in [MONTH/SEASON] for [SPECIFIC TARGET AUDIENCE] interested in [SPECIFIC TOPIC/INDUSTRY]?', 'status' => true],
            ['id' => 9, 'group' => 'Social Media', 'title' => 'Viral Tweet', 'prompt' => 'I want to create a tweet that will go viral and increase awareness about [topic]. Can you help me come up with a catchy headline and engaging content for the tweet?', 'status' => true],
            ['id' => 10, 'group' => 'Social Media', 'title' => 'Instagram Caption', 'prompt' => 'Create a caption for my latest Instagram post about [product/service] that will entice users to check it out and consider making a purchase.', 'status' => true],
            ['id' => 11, 'group' => 'Social Media', 'title' => 'YouTube Channel Growth', 'prompt' => 'What are some tips to grow a YouTube channel with a [topic] topic?', 'status' => true],
            ['id' => 12, 'group' => 'Social Media', 'title' => 'Viral TikTok Video', 'prompt' => 'I want to create a viral TikTok video about [topic]. Can you help me come up with a script that will capture the attention of my target audience and make them want to share it with their friends?', 'status' => true],
            ['id' => 13, 'group' => 'Social Media', 'title' => 'Twitter Traffic', 'prompt' => 'I want to create a tweet that will drive traffic to my website or blog. Can you help me write a tweet that includes a link to my website or blog and a compelling call-to-action related to [topic]?', 'status' => true],
            ['id' => 14, 'group' => 'Content', 'title' => 'Paragraph Generator', 'prompt' => 'Write a paragraph about [topic]. Make sure to include keywords: [keywords]. Write it in a [Tone].', 'status' => true],
            ['id' => 15, 'group' => 'Content', 'title' => 'Article Generator', 'prompt' => 'Write a 500-word article about [topic]. Make sure to include keywords: [keywords]. Write it in a [Tone].', 'status' => true],
            ['id' => 16, 'group' => 'Content', 'title' => 'Product Description', 'prompt' => 'Write a product description about [product name and description]. Write it in the 2nd person perspective, make it 3 paragraphs long.', 'status' => true],
            ['id' => 17, 'group' => 'Content', 'title' => 'Brochure Generator', 'prompt' => 'Create a brochure outlining the features and benefits of [product]. Include customer testimonials and a call to action. Product details: [additional product details]', 'status' => true],
            ['id' => 18, 'group' => 'Content', 'title' => 'Product Review Creator', 'prompt' => 'Write a product review for [Product]. Give it a [Rating] rating and highlight its pros and cons. In terms of pros, focus on the [Pros_1], [Pros_2], and [Pros_3]. However, mention the [Cons] as a con. Write a [Word_Count] word review that provides a balanced assessment and would help potential customers make an informed decision.', 'status' => true],
            ['id' => 19, 'group' => 'Content', 'title' => 'FAQ Generator', 'prompt' => 'Create a list of [8] frequently asked questions about [keyword] and provide answers for each one of them considering the SERP and rich result guidelines.', 'status' => true],
            ['id' => 20, 'group' => 'Content', 'title' => 'PASTOR Framework', 'prompt' => 'Write a copy using the "PASTOR" framework to address the pain points of [ideal customer persona] and present our [product/service] as the solution. Identify the [problem] they are facing, amplify the consequences of not solving it, tell a [story] related to the problem, include [testimonials] from happy customers, present our [offer], and ask for a response.', 'status' => true],
            ['id' => 21, 'group' => 'Content', 'title' => 'AIDA (Attention-Interest-Desire-Action) Framework', 'prompt' => 'Write a copy using the "Attention-Interest-Desire-Action" framework to grab the attention of ideal customer persona] and persuade them to take action. Start with a bold statement to get their attention, present information that piques their [interest], state the benefits of our [product/service] to create [desire], and ask for a signup or purchase.', 'status' => true],
            ['id' => 22, 'group' => 'Content', 'title' => 'PAS (Problem-Agitate-Solve) Framework', 'prompt' => 'Write a copy using the "Problem-Agitate-Solve" framework to address the pain points of [ideal customer persona] and present our [product/service] as the solution. Identify the [problem] they are facing, amplify the consequences of not solving it, and present our [offer] as the solution.', 'status' => true],
            ['id' => 23, 'group' => 'Content', 'title' => 'Podcast Episode Plan', 'prompt' => 'Can you help me plan a podcast episode on [TOPIC], by identifying [NUMBER] different viewpoints on [SUBTOPIC] and providing [TYPE OF INFORMATION] on each, while incorporating [TYPE OF MEDIA] to add depth and interest?"', 'status' => true],
            ['id' => 24, 'group' => 'Content', 'title' => 'Gift Finder', 'prompt' => 'Create a list of gift ideas for a [Recipient] who is interested in [Interests] and falls within the [Price_Range] price range. Your goal is to provide a range of options that would appeal to someone with those interests while staying within their budget. Be creative and consider incorporating aspects of all three interests for a unique and thoughtful gift.', 'status' => true],
            ['id' => 25, 'group' => 'Sales', 'title' => 'Brainstorm Pain Points', 'prompt' => 'Act as a [target persona]. What pain points do they face and what language would they use for [goals]?', 'status' => true],
            ['id' => 26, 'group' => 'Sales', 'title' => 'Competitor Analysis', 'prompt' => 'Conduct a full analysis of [competitor company name] and identify the competitive advantages and disadvantages of their product.', 'status' => true],
            ['id' => 27, 'group' => 'Sales', 'title' => 'Sales Pitch', 'prompt' => 'Write a sales pitch for [product name] that will convince [target persona] to purchase it. Here are some additional details about the product: [add details]', 'status' => true],
            ['id' => 28, 'group' => 'Sales', 'title' => 'Sales Email', 'prompt' => 'Write a sales email to [target persona] about [product name]. Here are some additional details about the product: [add details]', 'status' => true],
            ['id' => 29, 'group' => 'Sales', 'title' => 'Personalized Cold DM Writer', 'prompt' => 'Write a personalized cold DM to [Customer_Persona] that promotes [Subject] and encourages them to [Desired_Action]. Use a friendly and professional tone throughout the message and provide a clear call-to-action for them.', 'status' => true],
            ['id' => 30, 'group' => 'SEO', 'title' => 'SEO Keyword Research', 'prompt' => 'Write a list of 10 SEO keywords on [topic] that will help it rank higher on Google. Cluster this list of keywords according to funnel stages whether they are top of the funnel, middle of the funnel or bottom of the funnel keywords.', 'status' => true],
            ['id' => 31, 'group' => 'SEO', 'title' => 'SEO Meta Description', 'prompt' => 'Write a meta description for [product name] that will help it rank higher on Google.', 'status' => true],
            ['id' => 32, 'group' => 'SEO', 'title' => 'SEO Content Generator', 'prompt' => 'I am looking to expand the content on my website, but I am struggling to come up with new ideas. Can you help me generate a list of possible topics for my [industry/niche] business?', 'status' => true],
            ['id' => 33, 'group' => 'SEO', 'title' => 'Blog Post Titles', 'prompt' => 'Give me 10 SEO-optimized titles for a blog post about [topic]. Make sure to include keywords: [keywords].', 'status' => true],
            ['id' => 34, 'group' => 'SEO', 'title' => 'Question Researcher', 'prompt' => 'Provide a list of 10 questions that people are asking about "AI Copywriting".', 'status' => true],
            ['id' => 35, 'group' => 'SEO', 'title' => 'SEO Content Brief', 'prompt' => 'Create a SEO content brief for [keyword].', 'status' => true],
            ['id' => 36, 'group' => 'SEO', 'title' => 'Search Intent Researcher', 'prompt' => 'Provide 10 long tail keywords related to [topic]. Match each keyword with any of the 4 types of search intent.', 'status' => true],
        ];

        foreach ($ads as $ad) {
            ChatPrompt::updateOrCreate(['id' => $ad['id']], $ad);
        }
    }
}
