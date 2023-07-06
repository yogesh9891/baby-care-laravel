<html>
<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 3cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
                font-family:Arial,serif;
                font-size:14px;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
<<<<<<< HEAD
                 height: 2.5cm;
=======
                height: 3cm;
                background-color: #fff;
            }
            p {
                text-align: justify;
>>>>>>> new_deployment
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
            }
        </style>
</head> 
<body>
      
            <header>
                <img src="{{asset('images/header.jpg')}}" width="100%" />
            </header>
       

        <footer>
            <img src="{{asset('images/footer.png')}}" width="100%" height="100%"/>
        </footer>

         <main>
            <div style="margin-top: 200px;display: block;">
                 <div style="float: left;">
                 <img src="{{asset('images/helicopter.png')}}" width="100%">
            </div>
              <div style="float: right;">
                 <img src="{{asset('images/kite.png')}}" width="100%">
            </div>
        </div>
<div style="text-align: center; padding-top: 50px;padding-bottom: 50px;align-items: center;margin: 0 auto ;">
    <img src="{{asset('images/boy.png')}}" width="15%" ><br>
    <br>
    <div style="text-align: center;">
    <img src="{{asset('images/logo.png')}}" width="50%">
</div>
    <br>
    <p style="font-size:36.0px;color:rgb(255,102,153);font-weight:bold;font-style:normal;text-decoration: none;text-align: center;">MEETS</p><br>
    <span style="font-size:40px;color:rgb(0,31,95);font-weight:bold;font-style:normal;text-decoration: none;text-align: center;">{{strtoupper($name)}}...!!!</span><
    <br>
    <br>
    <br>
<<<<<<< HEAD
    <br>
    <br>
    <p style="font-size:20.1px;color:rgb(0,31,95);font-weight:normal;font-style:normal;text-decoration: none">Reference Number: <span style="font-size:20.1px;color:rgb(255,102,153);font-weight:normal;font-style:normal;text-decoration: none">{{date('Ymd')}}-{{$id}}-{{strtoupper($name)}}</span> </p>
    <p style="font-size:20.1px;color:rgb(0,31,95);font-weight:normal;font-style:normal;text-decoration: none">{{ucfirst($name)}}’s Age at Self-assessment:<span style="font-size:20.1px;color:rgb(255,102,153);font-weight:normal;font-style:normal;text-decoration: none"> {{$age}}  Months </span> </p>
=======
    <p style="font-size:20.1px;color:rgb(0,31,95);font-weight:normal;font-style:normal;text-decoration: none;text-align: center;">Reference Number: <span style="font-size:20.1px;color:rgb(255,102,153);font-weight:normal;font-style:normal;text-decoration: none;text-align: center;">{{date('Ymd')}}-{{$id}}-{{strtoupper($name)}}</span> </p>
    <p style="font-size:20.1px;color:rgb(0,31,95);font-weight:normal;font-style:normal;text-decoration: none;text-align: center;">{{ucfirst($name)}}’s Age at Self-assessment:<span style="font-size:20.1px;color:rgb(255,102,153);font-weight:normal;font-style:normal;text-decoration: none;text-align: center;"> {{$age}}  Months </span> </p>
>>>>>>> new_deployment
    <br>
</div>

            @php   
                 $r = $result> 1?floor($result):ceil($result); 
                $dage  =  $age + $r; 
@endphp
<div style="text-align: center;padding-top: 50px;padding-bottom: 50px;justify-content: center;">
    <img src="{{asset('images/thumbsup.png')}}">
</div>
<div>
    @if($result >= 1)
    <p>
<<<<<<< HEAD
        Dear {{ucfirst($name)}}’s Parents, <p>
=======
        Dear {{ucfirst($name)}}’s Parents, </p>
>>>>>>> new_deployment

Thank you for taking the self-assessment test…!<br>

<p>Based on your answers, CompassTot is happy to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be ahead of the Expected Normal Overall Developmental Curve by {{$r}} {{$r > 1?'Months':'Month'}} ...!! </p>


<p>That means, {{ucfirst($name)}}’s Overall Developmental Age is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} Yippee…!!! <img src="{{asset('images/cap.png')}}" width="20">   
<img src="{{asset('images/smile.png')}}" width="20">   
<img src="{{asset('images/cup.png')}}" width="20">      </p>
<p>
Let us make sure that {{ucfirst($name)}}  continues to remain ahead of the Expected Normal Overall Developmental Curve going forward too and does not lose {{$gender2}} lead because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to improve the extent and quality of {{ucfirst($name)}}’s developmental lead, without putting any undue pressure on {{$gender3}}.
</p>
<p>
How do we do that…? We do that with the help of neuroscience based, developmentally appropriate, play-based fun activities, tailored to {{ucfirst($name)}}’s unique & evolving growth path. All our activities are designed in ways Children and Parents enjoy it, which improves quality of time you spend with {{ucfirst($name)}}  & builds an even stronger bond between you. This in effect, helps in improving learning outcomes.
</p>
<p>
Simply put, we assist you with play-based fun activities that will be specifically curated for {{ucfirst($name)}}  to make sure that you don’t miss out on giving {{$gender3}} the right exposure, at the right time & in the right way, to aid {{$gender2}} skill development & achievement of important milestones in various domains. Furthermore, we focus not only on {{ucfirst($name)}}’s pace of developmental progression, but also on the quality of {{$gender2}} developmental progression – as both play an important role in determining whether the resulting neural connection that form as strong or weak.
</p>
<p>
We hope that you have already started using your <b>1-MONTH FREE TRIAL</b> after requesting for your report. As you would have noticed during trial activation, we do not ask for your credit card details while starting your Free Trial. All we request you is to make the most of your Free Trial, so that it’ll benefit most to {{ucfirst($name)}} .  
</p>
<p>
(Please note, if you did not register for your Free Trial immediately after requesting for your report, you may need to give this test again, for us to be able to curate activities for {{ucfirst($name)}}  according to {{$gender3}} current developmental stage.)
<p>
    <p>
In the following sections, we have given an overview of various domains of development and {{ucfirst($name)}}’s developmental age in each of them.

    </p>
   
    @elseif($result <1 && $result >-1)

<p>Dear {{ucfirst($name)}}’s Parents, <p>

<p>Thank you for taking the self-assessment test…!</p>
<p>

Based on your answers, CompassTot is happy to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be on the Expected Normal Overall Developmental Curve…!! (Note )  
</p>
<p>
That means, {{ucfirst($name)}}’s Overall Developmental Age is also likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . Yippee…!!!      
</p>
<p>
Let us make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Overall Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to give {{ucfirst($name)}}  a slight lead and help{{$gender3}} get ahead of the Expected Normal Overall Developmental Curve, by improving the extent and quality of {{ucfirst($name)}}’s developmental progression, without putting any undue pressure on {{$gender3}}.
</p>
<p>
How do we do that…? We do that with the help of neuroscience based, developmentally appropriate, play-based fun activities, tailored to your Child’s unique & evolving growth path. All our activities are designed in ways Children and Parents enjoy it, which improves quality of time you spend with {{ucfirst($name)}}  & builds an even stronger bond between you. This in effect, helps in improving learning outcomes.
</p>
<p>
Simply put, we assist you with play-based fun activities that will be specifically curated for {{ucfirst($name)}}  to make sure that you don’t miss out on giving{{$gender3}} the right exposure, at the right time & in the right way, to aid {{$gender3}} skill development & achievement of important milestones in various domains. Furthermore, we focus not only on {{ucfirst($name)}}’s pace of developmental progression, but also on the quality of {{$gender3}} developmental progression – as both play an important role in determining whether the resulting neural connection that form as strong or weak.
</p>
<p>
We hope that you have already started using your <b>1-MONTH FREE TRIAL</b> after requesting for your report. As you would have noticed during trial activation, we do not ask for your credit card details while starting your Free Trial. All we request you is to make the most of your Free Trial, so that it’ll benefit most to {{ucfirst($name)}} .  
</p>
<p>
(Please note, if you did not register for your Free Trial immediately after requesting for your report, you may need to give this test again, for us to be able to curate activities for {{ucfirst($name)}}  according to {{$gender3}} current developmental stage.)
</p>
<p>
In the following sections, we have given an overview of various domains of development and {{ucfirst($name)}}’s developmental age in each of them.
</p>

    @elseif($result <= -1)
  
<p>Dear {{ucfirst($name)}}’s Parents, </p>
<p>
Thank you for taking the self-assessment test…!
</p>
<p>
Based on your answers, CompassTot is concerned to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be behind on the Expected Normal Overall Developmental Curve by {{abs($r)}} {{$r > 1?'Months':'Month'}} . 
</p>
<p>
That means, {{ucfirst($name)}}’s Overall Developmental Age is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . So, we need to buckle up.
</p>
<p>
First things first, let’s not panic and start putting {{ucfirst($name)}}  under pressure, as Children do not learn at a constant pace all the time. So, this lag could be because of that or maybe simply because of not being exposed to developmentally appropriate activities due to which {{ucfirst($name)}}  never had an opportunity to learn some skills. In any case, we can address those issues going forward. So, let us try our best to make sure that {{ucfirst($name)}}  recovers from the lag & gets back on the Expected Normal Overall Developmental Curve at earliest possible, without putting any undue pressure on {{$gender3}}. Once we achieve that, our next priority should be to make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Overall Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way.
</p>
<p>
How do we do that…? We do that with the help of neuroscience based, developmentally appropriate, play-based fun activities, tailored to {{ucfirst($name)}}’s unique & evolving growth path. All our activities are designed in ways Children and Parents enjoy it, which improves quality of time you spend with {{ucfirst($name)}}  & builds an even stronger bond between you. This in effect, helps in improving learning outcomes.
</p>
<p>
Simply put, we assist you with play-based fun activities that will be specifically curated for {{ucfirst($name)}}  to make sure that you don’t miss out on giving{{$gender3}} the right exposure, at the right time & in the right way, to aid{{$gender2}} skill development & achievement of important milestones in various domains. Furthermore, we focus not only on {{ucfirst($name)}}’s pace of developmental progression, but also on the quality of{{$gender2}} developmental progression – as both play an important role in determining whether the resulting neural connection that form as strong or weak.
</p>
<p>
We hope that you have already started using your <b>1-MONTH FREE TRIAL</b> after requesting for your report. As you would have noticed during trial activation, we do not ask for your credit card details while starting your Free Trial. All we request you is to make the most of your Free Trial, so that it’ll benefit most to {{ucfirst($name)}} .  
</p>
<p>
(Please note, if you did not register for your Free Trial immediately after requesting for your report, you may need to give this test again, for us to be able to curate activities for {{ucfirst($name)}}  according to{{$gender2}} current developmental stage.)
</p>
<p>
In the following sections, we have given an overview of various domains of development and {{ucfirst($name)}}’s developmental age in each of them.
</p>
    <br>
    <br>
    <br>
    <br>


    @endif


 </div>
<div style="padding-top: 50px;padding-bottom: 120px;justify-content: center">
    <b>DOMAINS OF DEVELOPMENT IN EARLY YEARS:</b>
   {{--  <div style="text-align:center;margin: 40px;">
        <img src="{{asset('images/domain.jpg')}}" width="50%">
    </div> --}}
  

<b>What is Development…? </b>
<p>The learning processes your Child goes through as they grow and become an adult is called development. Skills are learnt and then combined to develop more complex tasks such as walking, talking, playing, critical reasoning, social interacting etc.
</p>

<b>Why are the Early Years so Important…?</b>
<p>
The quality of nurturing & support provided in the early years influences a Child’s ability to learn, their behaviour, their ability to control emotions and their risks for disease later in life.
</p>
<p>
Children experience the greatest rate of development during their early years and the first 6 years of life are a critically important time in brain development. Over 85% of cumulative brain development is completed prior to age 6. While neural connections in the brain are made throughout life, the rapid pace at which our brains develop in these first 6 years is never repeated.
</p>

<b>But isn’t IQ Fixed at Birth…?</b>

<p>IQ is NOT fixed at birth. In the first few years of life, 10,00,000+ new neural connections are formed in the Brain, Every Second…! The stimulation a Child receives in the first few years, improves the brain.
</p>
<p>
But parents get limited amount of time to make it count. 85% of cumulative brain development is completed prior to age 6, thus, Ages 0-6 are called Golden Window of Opportunity.
</p>
<p>Brain is most flexible or “plastic” early in life, but its capacity for change decreases with age. As maturing brain becomes more specialized to assume more complex functions, it’s less capable of reorganizing & adapting to new or unexpected challenges. The timing is genetic, but early experiences determine whether the neural circuits are strong or weak. Thus, it’s easier & more effective to influence a Baby’s developing brain architecture early (0-6 Ages), than to rewire parts of its circuitry later.
</p>

<B>Which Core Domains of Development does CompassTot Focus on…?</B>
<p>CompassTot focuses on following core domains of development:
<ul style="list-style: none;">
<li>1.  Gross Motor Skills, such as crawling, jumping or running</li>
<li>2.  Fine Motor Skills, such as writing and drawing</li>
<li>3.  Cognitive and Intellectual Skills, such as counting or identifying shapes</li>
</ul>
</p>
    <p>So our priority, specifically for this domain of development, should be to make sure that {{$gender1}} continues to remain ahead of the normal developmental curve going forward too and does not lose {{$gender2}} lead because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to improve the extent and quality of {{$gender2}} lead, without putting any undue pressure on {{$gender3}}.</p>
<<<<<<< HEAD

=======
>>>>>>> new_deployment

</div>
{{-- Skillls Based --}}




 @foreach($skills as  $skill => $value)

{{-- Gross Motor --}}

  @if($skill =='Gross Motor')

<div style="padding-top: 45px;padding-bottom: 120px;justify-content: center">
 <p> <b>1. GROSS MOTOR SKILLS </b></p>
    <div style="text-align:center;margin-bottom: 60px;">
        <img src="{{asset('images/gross.jpg')}}" width="50%">
    </div>
    <b>What are Gross Motor Skills…?</b>
<p>Gross motor (physical) skills are those which require whole body movement and which involve the large (core stabilizing) muscles of the body to perform everyday functions, such as standing, walking, running, jumping and sitting upright at the table. They also include skills which require eye-hand coordination (such as ball skills like throwing) and bilateral motor coordination (such as running & jumping).
</p>
<p> 
Children with well-developed gross motor abilities would have above average movement and balance skills. They are likely to be Children who could be described as agile, well-coordinated, and graceful in their movements.
</p>
<p>
Children with poor gross motor abilities would have weak movement and balance skills. These Children may have difficulty in learning to crawl, climb, walk and run. A deficit in gross motor abilities can be mild and the Child's movements may be described as clumsy and uncoordinated. More severe gross motor problems may limit a Child's use of their legs to such a degree that they will need assistance to move from place to place.
</p>
<p>
Gross motor skills are important to enable Children to perform everyday functions, such as walking and running, playground skills (for e.g., climbing) and sporting skills (for e.g., throwing & kicking a ball, riding a tricycle etc.). However, these skills are also crucial for everyday self-help skills like dressing (where you need to be able to stand on one leg to put your leg into a pant leg without falling over) and climbing into and out of a car or even getting into and out of bed.
</p>
	<div style="padding-top: 45px;justify-content: center">
<p>
Gross motor abilities also have an influence on other everyday functions. For example, a Child’s ability to maintain appropriate tabletop posture (upper body support) will affect their ability to participate in fine motor skills (for e.g., building a tower with blocks, putting pegs in a pegboard) and sitting upright to attend to class instructions in a playschool, which will then have an impact on their learning. Gross motor skills impact endurance to cope with duration of playschool (for e.g., sitting upright, moving between classrooms, carrying a school bag etc.). They also impact the ability to navigate environment (for e.g., walking around classroom items such as a desk, up a sloped playground hill or to get on and off a moving escalator). Without fair gross motor skills, a Child will struggle with many day-to-day tasks such as eating, packing away their toys and getting onto and off the toilet or potty.
</p>
<p>
Children with gross motor difficulties commonly display:
</p>
<ul>
<li>Avoidance or general disinterest in physical tasks</li>
<li> Rush task performance of physical tasks (to mask difficulty or fatigue)</li>
<li>Silly task performance of physical task they find challenging</li>
<li>Bossiness in telling others how to do the physical task or play the game without actively engaging themselves</li>
</ul>

<p style="color: red;">Red Flags for Gross Motor Development (Age 5 - 6 Years):</p>

<p>If you notice any of the following things about your Child, you should talk to your general physician or a specialist health professional such as a developmental pediatrician or a pediatric occupational therapist:</p>
<ul>
<li>Child is clumsy</li>
<li>Child is not able to catch a small ball</li>
<li>Child is not able to ride a bicycle without side wheels</li>
<li>Child is not able to play hopscotch, cricket, football etc.</li>
<li>Child cannot skip a rope</li>

</ul>

<b>{{ucfirst($name)}}’s Developmental Age in Gross Motor Domain:</b>  

 @if($value->value >= 1)
 @php   
                $dage  =  $age + floor($value->value);

@endphp
<p> Based on your answers, CompassTot is happy to report that as at {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}}, {{ucfirst($name)}}  seems to be ahead of the Expected Normal Gross Motor Developmental Curve by {{floor($value->value)}} {{$value->value > 1?'Months':'Month'}} ...!! 
</p>
<p>
That means, {{ucfirst($name)}}’s Developmental Age in Gross Motor domain is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . Yippee…!!!   
<img src="{{asset('images/cap.png')}}" width="20">   
<img src="{{asset('images/smile.png')}}" width="20">   
<img src="{{asset('images/cup.png')}}" width="20">   
</p>

<p>So, our priority, specifically for this domain of development, should be to make sure that {{ucfirst($name)}}  continues to remain ahead of the Expected Normal Gross Motor Developmental Curve going forward too and does not lose {{$gender2}} lead because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to improve the extent and quality of {{ucfirst($name)}}’s developmental lead, without putting any undue pressure on {{$gender3}}
</p>

 @elseif($value->value < 1 && $value->value >-1 )
 @php   
                $dage  =  $age + floor($value->value);

@endphp
<p> Based on your answers, CompassTot is happy to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be on the Expected Normal Gross Motor Developmental Curve…!! (Note )  
</p>
<p>
That means, {{ucfirst($name)}}’s Developmental Age in Gross Motor domain is also likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . Yippee…!!!
<img src="{{asset('images/cap.png')}}" width="20">   
<img src="{{asset('images/smile.png')}}" width="20">   
<img src="{{asset('images/cup.png')}}" width="20">   
</p>      
<p>
Let us make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Gross Motor Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to give {{ucfirst($name)}}  a slight lead and help{{$gender3}} get ahead of the Expected Normal Gross Motor Developmental Curve, by improving the extent and quality of {{ucfirst($name)}}’s developmental progression, without putting any undue pressure on {{$gender3}}.
</p>
 @elseif($value->value <= -1)
 @php   
                $dage  =  $age + ceil($value->value);

@endphp
 <p>Based on your answers, CompassTot is concerned to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be behind on the Expected Normal Gross Motor Developmental Curve by {{abs(ceil($value->value))}} {{ceil($value->value) > 1?'Months':'Month'}} . 
</p>
<p>That means, {{ucfirst($name)}}’s Developmental Age in Gross Motor domain is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . So, we need to buckle up.</p>

<p>First things first, let’s not panic and start putting {{ucfirst($name)}}  under pressure, as Children do not learn at a constant pace all the time. So, this lag could be because of that or maybe simply because of not being exposed to developmentally appropriate activities due to which {{ucfirst($name)}}  never had an opportunity to learn some skills. In any case, we can address those issues going forward. So, let us try our best to make sure that {{ucfirst($name)}}  recovers from the lag & gets back on the Expected Normal Gross Motor Developmental Curve at earliest possible, without putting any undue pressure on {{$gender3}}. Once we achieve that, our next priority should be to make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Gross Motor Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way.

</p>

</p>
</div>
</div>

 @endif

{{-- Skillls Based --}}


  @elseif($skill =='Fine Motor')
<div style="padding-top: 45px;justify-content: center">
<p><b>2.   FINE MOTOR SKILLS </b></p>

  <div style="text-align:center;">
        <img src="{{asset('images/fine.png')}}" width="50%">
    </div>

<b>What are Fine Motor Skills…?</b>
<p>
Fine motor skills involve the use of the smaller muscle of the hands, commonly in activities like stacking blocks, holding & scribbling with crayons, opening & closing taps, stringing large beads, opening boxes etc.
</p>
<p>
Fine motor skill efficiency significantly influences the quality of the task outcome as well as the speed of task performance. Efficient fine motor skills require several independent skills to work together to appropriately manipulate the object or perform the task.
</p>
<p>
Children with well-developed fine motor abilities would have above average skills such as picking up small objects, colouring pictures and stringing beads. They are likely to be described as good with their hands.
</p>
<p>
Children with poor fine motor abilities would have weak grasping and visual-motor skills. They have difficulty in learning to pick up objects, holding scissors in one hand and playing with small toys. A fine motor deficit can be mild; the Child's skills may be described as immature. Some Children may have problems severe enough to need specially designed utensils to feed themselves.
</p>
<p>
Fine motor skills include following:<br>

Academics skills including</p>
<ul>
<li>Pencil skills (colouring, drawing, writing)g </li>
<li>Scissors skills (cutting)</li>
</ul>
<p>Play </p>
<ul> 
<li>  Construction skills using lego, duplo, puzzles, train tracks
 </li>
<li>Doll dressing and manipulation </li>
<li> Fixing a simple 2-piece jigsaw puzzle</li>
</ul>
<p>Self-help skills including</p>


<ul style="padding-bottom: 50px">
<li>Dressing – tying shoelaces, doling up sandals, zips, buttons, belts</li>
<li>Eating – using cutlery, opening lunch boxes, opening food bags, unwrapping food wrappers </li>
<li>Hygiene – cleaning teeth, brushing hair, toileting</li>
</ul>
<p>
Note: Visual perception (accurately using vision, ‘seeing’ and interpreting) is not strictly a fine motor skill but directly supports fine motor skill performance.
</p>
<p>
Fine motor skills are essential for performing everyday skills as outlined above as well as preacademic skills. Without the ability to complete these everyday tasks, a Child’s self-esteem can suffer, their performance in playschool is compromised and their play options are very limited. They are also unable to develop appropriate independence in ‘life’ skills (such as getting dressed and feeding themselves) which in turn has social implications not only within the family but also within peer relationships.
</p>
<p>
When a Child has difficulties with fine motor skills, they might show following behaviour:
</p>
<ul>
<li> Avoidance and/or disinterest in tasks involving fine motor skills (such as tasks listed above)</li>
<li> Preferring physical activity (again to avoid sit down tasks)</li>
<li> Interest in ‘passive’ activities (such as watching TV, Mobile etc.) that don’t require Fine Motor skills</li>
<li> No interest in crayon or scissors skills</li>
<li> Being ‘bossy’ in play and asking others to do things for them, such as construct a tower of blocks or fix a jigsaw puzzle. </li>
<li> Not persisting in the face of a challenge (for e.g., asking parents to fix a problem without physically trying to fix it themselves)</li>
<li> Waiting for parents to dress them or feed them, rather than trying themselves</li>

</ul>
<p style="color:red;">
Red Flags for Fine Motor Development (Age 5 - 6 Years):
</p>
<p>
If you notice any of the following things about your Child, you should talk to your general physician or a specialist health professional such as a developmental pediatrician or a pediatric occupational therapist:</p>
<ul>
<li>Child cannot write words on 4-line notebook</li>
<li>Child cannot draw designs and human figures using a pencil</li>
<li>Child cannot cut shapes, curves and designs on a paper</li>
<li>Child cannot do a paper craft which requires folding and creasing paper more than once</li>
<li>Child cannot use handle small lego blocks and construct a 3-dimensional structure</li>
<li>Child cannot colour small designs using a colour pencil</li>

</ul>

<b>{{ucfirst($name)}}’s Developmental Age in Fine Motor Domain:</b>

 @if($value->value >= 1)
 @php   
                $dage  =  $age + floor($value->value);

@endphp
<p> Based on your answers, CompassTot is happy to report that as at {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}}, {{ucfirst($name)}}  seems to be ahead of the Expected Normal Fine Motor Developmental Curve by {{floor($value->value)}} {{$value->value > 1?'Months':'Month'}} ...!! 
</p>
<p>
That means, {{ucfirst($name)}}’s Developmental Age in Fine Motor domain is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . Yippee…!!!   
<img src="{{asset('images/cap.png')}}" width="20">   
<img src="{{asset('images/smile.png')}}" width="20">   
<img src="{{asset('images/cup.png')}}" width="20">   
</p>

<p>So, our priority, specifically for this domain of development, should be to make sure that {{ucfirst($name)}}  continues to remain ahead of the Expected Normal Fine Motor Developmental Curve going forward too and does not lose {{$gender2}} lead because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to improve the extent and quality of {{ucfirst($name)}}’s developmental lead, without putting any undue pressure on {{$gender3}}
</p>

 @elseif($value->value < 1 && $value->value >-1 )
 @php   
                $dage  =  $age + floor($value->value);

@endphp
<p> Based on your answers, CompassTot is happy to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be on the Expected Normal Fine Motor Developmental Curve…!! (Note )  
</p>
<p>
That means, {{ucfirst($name)}}’s Developmental Age in Fine Motor domain is also likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . Yippee…!!!
<img src="{{asset('images/cap.png')}}" width="20">   
<img src="{{asset('images/smile.png')}}" width="20">   
<img src="{{asset('images/cup.png')}}" width="20">   
</p>      
<p>
Let us make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Fine Motor Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to give {{ucfirst($name)}}  a slight lead and help{{$gender3}} get ahead of the Expected Normal Fine Motor Developmental Curve, by improving the extent and quality of {{ucfirst($name)}}’s developmental progression, without putting any undue pressure on {{$gender3}}.
</p>
 @elseif($value->value <= -1)
 @php   
                $dage  =  $age + ceil($value->value);

@endphp
 <p>Based on your answers, CompassTot is concerned to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be behind on the Expected Normal Fine Motor Developmental Curve by {{abs(ceil($value->value))}} {{ceil($value->value) > 1?'Months':'Month'}} . 
</p>
<p>That means, {{ucfirst($name)}}’s Developmental Age in Fine Motor domain is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . So, we need to buckle up.</p>

<p>First things first, let’s not panic and start putting {{ucfirst($name)}}  under pressure, as Children do not learn at a constant pace all the time. So, this lag could be because of that or maybe simply because of not being exposed to developmentally appropriate activities due to which {{ucfirst($name)}}  never had an opportunity to learn some skills. In any case, we can address those issues going forward. So, let us try our best to make sure that {{ucfirst($name)}}  recovers from the lag & gets back on the Expected Normal Fine Motor Developmental Curve at earliest possible, without putting any undue pressure on {{$gender3}}. Once we achieve that, our next priority should be to make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Fine Motor Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way.

</p>


</div>
 @endif




  @elseif($skill =='Cognitive')
<div style="padding-top: 45px;padding-bottom: 40px;justify-content: center">
<p ><b>3.   COGNITIVE SKILLS  </b></p>

  <div style="text-align:center;">
        <img src="{{asset('images/coginitive.png')}}" width="50%">
    </div>

<b>What are Cognitive Skills…?</b>

<p>Cognitive skills, also called cognitive functions, cognitive abilities or cognitive capacities, are brain-based skills which are needed in acquisition of knowledge, manipulation of information, and reasoning. They have more to do with the mechanisms of “HOW” people think, read, learn, remember, problem-solve, and pay attention, rather than with “Actual Knowledge” itself.
</p>
<p>
Cognitive functioning refers to a person's ability to process thoughts. It is defined as "the ability of an individual to perform the various mental activities most closely associated with learning and problem solving. The brain is usually capable of learning new skills in the aforementioned areas, typically in early Childhood, and of developing personal thoughts and beliefs about the world. Cognitive skills or functions encompass the domains of perception, attention, memory, learning, decision making, and language abilities.
</p>
<p>While individual cognitive skills or functions are specialized, they also overlap or interact with each other. That means, even if one of these skills is weak; grasping, retaining and using any new incoming information gets impacted. Most learning struggles are caused by one or more weak cognitive skills.
</p>
<p>
Most learning struggles are caused by one or more weak cognitive skills, as explained below:
</p>
<p>
Attention - Sustained<br>
What it does: Enables you to stay focused and on task for a sustained period of time.<br>
Common problems when this skill is weak: Lots of unfinished projects, jumping from task to task.<br>
</p>
<p>
Attention - Selective<br>
What it does: Enables you to stay focused and on a task despite distractions.<br>
Common problems when this skill is weak: Easily distracted.<br>
</p>
<p>
Attention - Divided<br>
What it does: Enables you to remember information while doing two things at once.<br>
Common problems when this skill is weak: Difficulty multitasking, frequent mistakes.<br>
</p>
<p>
Memory - Long-Term<br>
What it does: Enables you to recall information stored in the past.<br>
Common problems when this skill is weak: Forgetting names, doing poorly on tests, forgetting things you used to know.<br>
</p>
<p>
<<<<<<< HEAD
Memory - Working (Or Short-Term)<br>
=======
Memory - Working (Or Short-Term)<br></p>
<p style="padding-top: 35px" >
>>>>>>> new_deployment
What it does: Enables you to hang on to information while in the process of using it.<br>
Common problems when this skill is weak: Difficulty in following and remembering simple one step directions, forgetting what was just said in a conversation.<br>
</p>
<p>
Logic & Reasoning<br>
What it does: Enables you to reason, form ideas and solve problems.<br>
Common problems when this skill is weak: Frequently asking, “What do I do next?” or saying, “I don’t get this,” struggling with math, feeling stuck or overwhelmed and difficulty in understanding rules of any game.<br>

</p>
<p>
Auditory Processing<br>
What it does: Enables you to analyze, blend and segment sounds.<br>
Common problems when this skill is weak: Struggling with learning to read, reading fluency, or reading comprehension.<br>
</p>
<p>
Visual Processing<br>
What it does: Enables you to think in visual images.<br>
Common problems when this skill is weak: Difficulties understanding what you’ve just read, remembering what you’ve read, following directions.<br>
</p>
<p>
Processing Speed<br>
What it does: Enables you to perform tasks quickly and accurately.<br>
What it does: Enables you to perform tasks quickly and accurately.<br>
Common problems when this skill is weak: Most tasks are more difficult. Taking a long time to complete tasks for school or work, frequently being the last one in a group to finish something.


</p>

<b style="color:red;font-weight: bold;">
Red Flags for Cognitive Development (Age 5 - 6 Years):
</b>
<p>
If you notice any of the following things about your Child, you should talk to your general physician or a specialist health professional such as a developmental pediatrician or a pediatric occupational therapist:
</p>
<ul style="margin-top: -10px;">
<li> Child constantly moves from one activity to another and is not able to stay with one activity until it gets completed</li>
<li> Child is not interested in pretend play</li>
<li> Child cannot follow multiple instructions in any situation. For example, Child cannot sit at a desk, follow teacher’s instructions and do simple in-class assignments independently without any supervision or verbal clues</li>
<li> Child does not understand rules of a game and concept of turn taking</li>
<li> Child cannot recall & describe any activity</li>
<li> Child does not understand concepts like today, tomorrow, yesterday, first, next, last</li>

</ul>
</p>
<b>{{ucfirst($name)}}’s Developmental Age in Cognitive Motor Domain:</b>

 @if($value->value >= 1)
 @php   
                $dage  =  $age + floor($value->value);

@endphp
<p> Based on your answers, CompassTot is happy to report that as at {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}}, {{ucfirst($name)}}  seems to be ahead of the Expected Normal Cognitive Motor Developmental Curve by {{floor($value->value)}} {{$value->value > 1?'Months':'Month'}} ...!! 
</p>
<p>
That means, {{ucfirst($name)}}’s Developmental Age in Cognitive Motor domain is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . Yippee…!!!   
<img src="{{asset('images/cap.png')}}" width="20">   
<img src="{{asset('images/smile.png')}}" width="20">   
<img src="{{asset('images/cup.png')}}" width="20">   
</p>

<p>So, our priority, specifically for this domain of development, should be to make sure that {{ucfirst($name)}}  continues to remain ahead of the Expected Normal Cognitive Motor Developmental Curve going forward too and does not lose {{$gender2}} lead because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to improve the extent and quality of {{ucfirst($name)}}’s developmental lead, without putting any undue pressure on {{$gender3}}
</p>

 @elseif($value->value < 1 && $value->value >-1 )
 @php   
                $dage  =  $age + floor($value->value);

@endphp
<p> Based on your answers, CompassTot is happy to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be on the Expected Normal Cognitive Motor Developmental Curve…!! (Note )  
</p>
<p>
That means, {{ucfirst($name)}}’s Developmental Age in Cognitive Motor domain is also likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . Yippee…!!!
<img src="{{asset('images/cap.png')}}" width="20">   
<img src="{{asset('images/smile.png')}}" width="20">   
<img src="{{asset('images/cup.png')}}" width="20">   
</p>      
<p>
Let us make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Cognitive Motor Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way. And while we are at it, let us also try to give {{ucfirst($name)}}  a slight lead and help{{$gender3}} get ahead of the Expected Normal Cognitive Motor Developmental Curve, by improving the extent and quality of {{ucfirst($name)}}’s developmental progression, without putting any undue pressure on {{$gender3}}.
</p>
 @elseif($value->value <= -1)
 @php   
                $dage  =  $age + ceil($value->value);

@endphp
 <p>Based on your answers, CompassTot is concerned to report that as at  {{intVal($age/12)}} years {{($age%12)}} {{($age%12) > 1?'Months':'Month'}} , {{ucfirst($name)}}  seems to be behind on the Expected Normal Cognitive Motor Developmental Curve by {{abs(ceil($value->value))}} {{ceil($value->value) > 1?'Months':'Month'}} . 
<<<<<<< HEAD
</p>
<p>That means, {{ucfirst($name)}}’s Developmental Age in Cognitive Motor domain is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . So, we need to buckle up.</p>

<p>First things first, let’s not panic and start putting {{ucfirst($name)}}  under pressure, as Children do not learn at a constant pace all the time. So, this lag could be because of that or maybe simply because of not being exposed to developmentally appropriate activities due to which {{ucfirst($name)}}  never had an opportunity to learn some skills. In any case, we can address those issues going forward. So, let us try our best to make sure that {{ucfirst($name)}}  recovers from the lag & gets back on the Expected Normal Cognitive Motor Developmental Curve at earliest possible, without putting any undue pressure on {{$gender3}}. Once we achieve that, our next priority should be to make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Cognitive Motor Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way.
=======
That means, {{ucfirst($name)}}’s Developmental Age in Cognitive Motor domain is likely to be {{intVal($dage/12)}} years {{($dage%12)}} {{($dage%12) > 1?'Months':'Month'}} . So, we need to buckle up.<br>First things first, let’s not panic and start putting {{ucfirst($name)}}  under pressure, as Children do not learn at a constant pace all the time. So, this lag could be because of that or maybe simply because of not being exposed to developmentally appropriate activities due to which {{ucfirst($name)}}  never had an opportunity to learn some skills. In any case, we can address those issues going forward. So, let us try our best to make sure that {{ucfirst($name)}}  recovers from the lag & gets back on the Expected Normal Cognitive Motor Developmental Curve at earliest possible, without putting any undue pressure on {{$gender3}}. Once we achieve that, our next priority should be to make sure that {{ucfirst($name)}}  continues to remain on the Expected Normal Cognitive Motor Developmental Curve going forward too and does not fall behind because of missing out on doing right things, at the right time & in the right way.
>>>>>>> new_deployment

</p>

</div>
 @endif


  @endif
    


@endforeach

  <div style="text-align:center;">
        <img src="{{asset('images/compass.png')}}" width="20%">
    </div> 

    <p>
Dear {{ucfirst($name)}}’s Parents, 
</p>
<p>
CompassTot sincerely thanks you for taking time out and reading through the report…! We hope that this was helpful for you and it will give you a good understanding of different domains of development & how is {{ucfirst($name)}}  faring in each of those, which will assist you in deciding the direction that will maximize developmental progression of {{ucfirst($name)}} . 
</p>
<p>
As you know, developmental progression is not only about the speed of achieving milestones/skills, but also about the quality of those milestones/skills – as both play an important role in determining whether the resulting neural connection that form as strong or weak.
</p>
<p>
Early experiences affect quality of a Child’s brain architecture by establishing either a sturdy or a fragile foundation for all learning, health & behaviour that follow. Studies tracking student learning outcomes clearly demonstrate that Children who start out late, tend to stay behind throughout their school years.
</p>
<p>
However, generic age-based activity lists do half-hearted job. The crux lies in adapting to your Child’s needs, based on the unique developmental path (quality & pace of skill development in each of the Core Skillsets) of your Child, on an ongoing basis. For us at CompassTot, this has been and will always remain the singular focal point & an obsession. Anything that we do, has to rigorously satisfy this. Nothing else matters to us.
</p>

<p>
The quality of Core Skills Children learn during formative years reverberate throughout their entire life – be it School or College or Office, be it Personal Life or Professional Life, be it Academics or Sports or Performing Arts. For e.g., when a Child learns the skill of being in one place & maintaining focus for extended period, it comes in handy irrespective of the goal – Math, Cricket, Guitar, Coding or Balance Sheet.
</p>
<p>
CompassTot singularly focuses on Taking the Guesswork Out in Raising a Toddler, by being your Personalized Neuroscientific Compass, to Assist You Skillfully Navigate & Make the Best Possible Use of the Golden Window of Core Skills Development, so {{ucfirst($name)}}  can Discover {{$gender2}} Maximum Potential when he steps out in the real world.
</p>
<p>
We hope that you have already started using your 1-MONTH FREE TRIAL after requesting for your report. As you would have noticed during trial activation, we do not ask for your credit card details while starting your Free Trial. All we request you is to make the most of your Free Trial, so that it’ll benefit most to {{ucfirst($name)}} .  
</p>
<p >
(Please note, if you did not register for your Free Trial immediately after requesting for your report, you may need to give this test again, for us to be able to curate activities for {{ucfirst($name)}}  according to{{$gender2}} current developmental stage.)
</p>
        </main>

<br>
<p style="font-size: 10px;">
Note: <br>
This report is based on answers given by you to the self-assessment test. The report is intended for educational purposes only. It is not to be considered as a medical opinion / diagnosis. CompassTot shall not accept any liability for the same.
</p>
	{{-- <button onclick="window.print()">Print this page</button> --}}

</body>

</html>
