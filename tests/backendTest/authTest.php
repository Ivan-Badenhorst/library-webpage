<?php
/*use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\backend\auth;

class AuthTest extends TestCase
{
    private $session;
    private $entityManager;

    public function setUp(): void
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager->method('getRepository')
            ->willReturn($this->userRepository);

        $this->session = $this->getMockBuilder(SessionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testLogin()
    {
        $username = 'johndoe';
        $password = 'password';
        $rememberMe = true;
        $cookieFile = '/tmp/remember_me.txt';

        // Create the directory if it does not exist
        $cookieDir = dirname($cookieFile);
        if (!file_exists($cookieDir)) {
            mkdir($cookieDir, 0777, true);
        }

        // Create the file if it does not exist
        if (!file_exists($cookieFile)) {
            touch($cookieFile);
        }

        // Set up the user repository mock
        $user = new User();
        $user->setDisplayName($username);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $this->userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => $username])
            ->willReturn($user);

        // Set up the session mock
        $this->session->expects($this->once())
            ->method('set')
            ->with(auth::SESSION_KEY, $user->getId());

        // Call the login method
        $auth = new auth($this->entityManager, $this->session);
        $auth->login($username, $password, $rememberMe, $cookieFile);

        // Check that the cookie file was created
        $this->assertFileExists($cookieFile);
    }


    /*
        public function testLoginWithIncorrectPassword(): void
        {
            $user = new User();
            $user->setEmail('test@test.com');
            $user->setPassword(password_hash('password', PASSWORD_DEFAULT));
            $user->setLoginTries(0);

            $userRepository = $this->createMock(UserRepository::class);
            $userRepository->expects($this->once())
                ->method('findOneBy')
                ->willReturn($user);

            $this->entityManager->expects($this->once())
                ->method('getRepository')
                ->willReturn($userRepository);

            $this->session->expects($this->never())
                ->method('set');

            $result = $this->auth->login('test@test.com', 'wrongpassword', $this->session);

            $this->assertSame('password', $result);
        }

        public function testLoginWithUserNotFound(): void
        {
            $userRepository = $this->createMock(UserRepository::class);
            $userRepository->expects($this->once())
                ->method('findOneBy')
                ->willReturn(null);

            $this->entityManager->expects($this->once())
                ->method('getRepository')
                ->willReturn($userRepository);

            $this->session->expects($this->never())
                ->method('set');

            $result = $this->auth->login('nonexisting@test.com', 'password', $this->session);

            $this->assertSame('email', $result);
        }*/
}*/